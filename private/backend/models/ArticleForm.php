<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.7.2015
 * Time: 7:33
 */

namespace backend\models;


use backend\utilities\ContentForm;
use common\models\Article;
use common\models\ArticleCategory;
use common\models\Category;
use common\models\FileRecord;
use common\models\Image;
use common\models\LanguageRecord;
use common\models\LayoutRecord;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ArticleForm is model for articles managing
 * @package backend\models
 */
class ArticleForm extends ContentForm
{
	/** @var string date of content */
	public $content_date;
	/** @var string time of content */
	public $content_time;
	/** @var string end date of content */
	public $content_end_date;
	/** @var string end time of content */
	public $content_end_time;
	/** @var array of check boxes of categories */
	public $categoryBoxes;
	/** @var integer layout id */
	public $layout_id;
	/** @var string time stamp for order */
	public $order_time;

	/**
	 * ArticleForm constructor
	 *
	 * @param integer|null $item_id
	 * @param bool $copy
	 */
	public function __construct( $item_id = null, $copy = false ) {
		parent::__construct();
		if ( $item_id ) {
			/** @var $article Article */
			/** @noinspection PhpUndefinedMethodInspection */
			$article = Article::find()->with( 'tags' )->where( [ 'id' => $item_id ] )->one();
			$this->item_id = $article->id;
			$this->language_id = $article->language_id;
			if ( $article->image ) {
				$this->imageFilename = $article->image->filename;
			}
			$this->perex            = $article->perex;
			$this->title            = $article->title;
			$this->description      = $article->description;
			$this->content_date     = $article->content_date ? Yii::$app->formatter->asDate( $article->content_date,
				'dd.MM.y' ) : null;
			$this->content_end_date = $article->content_end_date ? Yii::$app->formatter->asDate( $article->content_end_date,
				'dd.MM.y' ) : null;
			$this->content_time     = $article->content_time ? Yii::$app->formatter->asTime( $article->content_time,
				'HH:mm' ) : null;
			$this->content_end_time     = $article->content_end_time ? Yii::$app->formatter->asTime( $article->content_end_time,
				'HH:mm' ) : null;
			if ( $article->active ) {
				$this->boxes[] = self::PROPERTY_ACTIVE;
			}
			if ( $article->public ) {
				$this->boxes[] = self::PROPERTY_PUBLIC;
			}
			$this->layout_id  = $article->layout_id;
			$this->order_time = Yii::$app->formatter->asDatetime( $article->order_time && ! $copy ? $article->order_time : time(),
				'dd.MM.y HH:mm' );
			$this->tagValues  = $article->tagValues;
		} else {
			$session = Yii::$app->session;
			if ( ! $session['language_id'] ) {
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			}
			$this->language_id = $session['language_id'];
			$this->order_time  = Yii::$app->formatter->asDatetime( time(), 'dd.MM.y HH:mm' );

			$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->boxes[] = self::PROPERTY_PUBLIC;
		}
		$this->categoryBoxes = $this->getCategoryBoxes();
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ 'title', 'required', 'on' => [ 'create', 'update' ] ],
			[ [ 'title', 'imageFilename' ], 'string', 'max' => 255 ],
			[ [ 'content_date', 'content_time', 'content_end_date', 'content_end_time' ], 'default', 'value' => null ],
			[ [ 'content_date', 'content_end_date' ], 'date', 'format' => 'dd.MM.y' ],
			[ [ 'content_time', 'content_end_time' ], 'date', 'format' => 'HH:mm' ],
			[ [ 'order_time' ], 'date', 'format' => 'dd.MM.y HH:mm' ],
			[
				[
					'perex',
					'description',
					'item_id',
					'language_id',
					'layout_id',
					'boxes',
					'categoryBoxes',
					'tagValues'
				],
				'safe'
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'imageFilename'    => Yii::t( 'back', 'Image for perex' ),
			'perex'            => Yii::t( 'back', 'Perex' ),
			'content_date'     => Yii::t( 'back', 'Date' ),
			'content_end_date' => Yii::t( 'back', 'End date' ),
			'content_time'     => Yii::t( 'back', 'Time' ),
			'content_end_time'     => Yii::t( 'back', 'End time' ),
			'title'            => Yii::t( 'back', 'Title' ),
			'description'      => Yii::t( 'back', 'Content' ),
			'item_id'          => Yii::t( 'back', 'Actual Item' ),
			'language_id'      => Yii::t( 'back', 'Language' ),
			'layout_id'        => Yii::t( 'back', 'Layout' ),
			'boxes'            => Yii::t( 'back', 'Properties' ),
			'categoryBoxes'    => Yii::t( 'back', 'Categories' ),
			'order_time'       => Yii::t( 'back', 'Time stamp for order' ),
			'tagValues'        => Yii::t( 'back', 'Tags' )
		];
	}

	/**
	 * Save Article model
	 *
	 * @param bool $insert
	 */
	public function saveArticle( $insert = true ) {
		$article = $insert === true ? new Article : Article::findOne( $this->item_id );
		$imageId = null;
		if ( ! isset( Yii::$app->request->post( 'ArticleForm' )['imageFilename'] ) ) {
			$this->imageFilename = null;
		}
		if ( $this->imageFilename ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$imageId = Image::find()->andWhere( [ 'filename' => $this->getImageName() ] )->scalar();
			if (!$imageId) {
				$imageId = FileRecord::saveFileFromFilename($this->getImageName());
			}
		}
		$this->image_id      = $imageId;
		$article->attributes = $this->toArray();
		if ( $this->content_date ) {
			$article->content_date = Yii::$app->formatter->asDate( $this->content_date, 'y-MM-dd' );
		}
		if ( $this->content_end_date ) {
			$article->content_end_date = Yii::$app->formatter->asDate( $this->content_end_date, 'y-MM-dd' );
		}
		if ( $this->content_time ) {
			$article->content_time = Yii::$app->formatter->asTime( $this->content_time, 'HH:mm' );
		}
		if ( $this->content_end_time ) {
			$article->content_end_time = Yii::$app->formatter->asTime( $this->content_end_time, 'HH:mm' );
		}
		$article->active     = ( is_array( $this->boxes ) && in_array( self::PROPERTY_ACTIVE, $this->boxes ) ) ? 1 : 0;
		$article->public     = ( is_array( $this->boxes ) && in_array( self::PROPERTY_PUBLIC, $this->boxes ) ) ? 1 : 0;
		$article->order_time = Yii::$app->formatter->asDatetime( $this->order_time, 'y-MM-dd HH:mm' );
		if ( $article->id ) {
			ArticleCategory::deleteAll( [ 'article_id' => $article->id ] );
		}
		$article->tagValues = $this->tagValues;
		if ($article->save( false )) {
			$this->item_id = $article->id;
		}
		if ( is_array( $this->categoryBoxes ) ) {
			foreach ( $this->categoryBoxes as $categoryBox ) {
				$articleCategory              = new ArticleCategory;
				$articleCategory->article_id  = $article->id;
				$articleCategory->category_id = $categoryBox;
				$articleCategory->save();
			}
		}
	}

	/**
	 * Deletes Article
	 * @throws \Exception
	 */
	public function deleteArticle() {
		/** @var $article Article */
		if ( $article = Article::findOne( $this->item_id ) ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$article->removeAllTagValues();
			$article->delete();
		}
	}

	/**
	 * Renders category input boxes
	 * @param null $pid
	 * @param int $i
	 */
	public function renderCategoryInputs( $pid = null, $i = 0) {
		/** @noinspection PhpUndefinedMethodInspection */
		$query = Category::find()->andWhere( [ 'language_id' => $this->language_id ] );
		if ($pid === null)
			$query->andWhere('ISNULL(parent_id)');
		else
			$query->andWhere(['parent_id' => $pid]);
		/** @noinspection PhpUndefinedMethodInspection */
		$items = $query->orderBy([ 'main' => SORT_DESC, 'title' => SORT_ASC])->all();
		foreach ( $items as $item ) {
			/** @var $item \common\models\Category */
			echo '<div class="checkbox">' . ($i > 0 ? '<span style="color: lightgrey">' : '') . str_repeat( '&nbsp;', $i*2 ) . ($i > 0 ? '</span>' : '') . ( $i > 0 ? ' ' : '' ) .
	        Html::checkbox('ArticleForm[categoryBoxes][]', array_search($item->id, $this->categoryBoxes) === false ? false : true, [
				'value' => $item->id,
				'label' => $item->title
			]) . '</div>';
			if ($item->hasItems()) {
				$this->renderCategoryInputs($item->id, ++$i);
				--$i;
			}
		}
	}

	/**
	 * Gets used category switches
	 * @return array
	 */
	protected function getCategoryBoxes() {
		return $this->item_id ? ( ArticleCategory::find()->select( 'category_id' )->andWhere( [ 'article_id' => $this->item_id ] )->column() ) : [ Category::getMainCategory( $this->language_id ) ];
	}

	/**
	 * Returns short image name
	 * @return string
	 */
	public function getImageName() {
		if ( strstr( $this->imageFilename, Yii::getAlias( '@web' ) . '/' . Yii::$app->params['imageUploadDir'] ) ) {
			$startPosition = strlen( Yii::getAlias( '@web' ) . '/' . Yii::$app->params['imageUploadDir'] );
			$filename      = substr( urldecode($this->imageFilename), $startPosition );
		} else {
			$filename = urldecode($this->imageFilename);
		}

		return $filename;
	}

	/**
	 * Returns layout options for dropdown
	 * @return array
	 */
	public function getLayoutListOptions() {
		return ArrayHelper::map( LayoutRecord::find()->where( [
			'content' => LayoutRecord::CONTENT_ARTICLE
		] )->activeStatus()->orderBy( [
			'main'  => SORT_DESC,
			'title' => SORT_ASC
		] )->all(), 'id', 'title' );
	}
}