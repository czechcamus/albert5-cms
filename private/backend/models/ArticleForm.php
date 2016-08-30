<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.7.2015
 * Time: 7:33
 */

namespace backend\models;


use common\models\Article;
use common\models\ArticleCategory;
use common\models\Category;
use common\models\Image;
use common\models\LanguageRecord;
use common\models\LayoutRecord;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class ArticleForm is model for articles managing
 * @package backend\models
 */
class ArticleForm extends Model
{
	/** @var integer actual page id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var integer image id */
	public $image_id;
	/** @var string image filename */
	public $imageFilename;
	/** @var string perex */
	public $perex;
	/** @var string date of content */
	public $content_date;
	/** @var string time of content */
	public $content_time;
	/** @var string end date of content */
	public $content_end_date;
	/** @var string title od page */
	public $title;
	/** @var string description of page */
	public $description;
	/** @var array of check boxes of properties */
	public $boxes;
	/** @var array of check boxes of categories */
	public $categoryBoxes;
	/** @var integer layout id */
	public $layout_id;
	/** @var string tag values */
	public $tagValues;

	const PROPERTY_ACTIVE = 1;
	const PROPERTY_PUBLIC = 2;

	/**
	 * ArticleForm constructor
	 * @param integer|null $item_id
	 * @param bool $copy
	 */
	public function __construct( $item_id = null, $copy = false ) {
		parent::__construct();
		if ($item_id) {
			/** @var $article Article */
			$article = Article::find()->with('tags')->where(['id' => $item_id])->one();
			if (!$copy)
				$this->item_id = $article->id;
			$this->language_id = $article->language_id;
			if ($article->image)
				$this->imageFilename = $article->image->filename;
			$this->perex = $article->perex;
			$this->title = $article->title;
			$this->description = $article->description;
			$this->content_date = $article->content_date ? Yii::$app->formatter->asDate($article->content_date, 'dd.MM.y') : null;
			$this->content_end_date = $article->content_end_date ? Yii::$app->formatter->asDate($article->content_end_date, 'dd.MM.y') : null;
			$this->content_time = $article->content_time ? Yii::$app->formatter->asTime($article->content_time, 'HH:mm') : null;
			if ($article->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			if ($article->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
			$this->layout_id = $article->layout_id;
			$this->tagValues = $article->tagValues;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];

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
			['title','required', 'on' => ['create', 'update']],
			[['title', 'imageFilename'], 'string', 'max' => 255],
			[['content_date', 'content_time', 'content_end_date'], 'default', 'value' => null],
			[['content_date', 'content_end_date'], 'date', 'format' => 'dd.MM.y'],
			[['content_time'], 'date', 'format' => 'HH:mm'],
			[['perex', 'description', 'item_id', 'language_id', 'layout_id', 'boxes', 'categoryBoxes', 'tagValues'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'imageFilename' => Yii::t('back', 'Image for perex'),
			'perex' => Yii::t('back', 'Perex'),
			'content_date' => Yii::t('back', 'Date'),
			'content_end_date' => Yii::t('back', 'End date'),
			'content_time' => Yii::t('back', 'Time'),
			'title' => Yii::t('back', 'Title'),
			'description' => Yii::t('back', 'Content'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'layout_id' => Yii::t('back', 'Layout'),
			'boxes' => Yii::t('back', 'Properties'),
			'categoryBoxes' => Yii::t('back', 'Categories'),
			'tagValues' => Yii::t('back', 'Tags')
		];
	}

	/**
	 * Save Article model
	 * @param bool $insert
	 */
	public function saveArticle($insert = true) {
		$article = $insert === true ? new Article : Article::findOne($this->item_id);
		$imageId = null;
		if (!isset(Yii::$app->request->post('ArticleForm')['imageFilename'])) $this->imageFilename = null;
		if ($this->imageFilename) {
			$imageId = Image::find()->andWhere( [ 'filename' => $this->getImageName() ] )->scalar();
		}
		$this->image_id = $imageId;
		$article->attributes = $this->toArray();
		if ($this->content_date)
			$article->content_date = Yii::$app->formatter->asDate($this->content_date, 'y-MM-dd');
		if ($this->content_end_date)
			$article->content_end_date = Yii::$app->formatter->asDate($this->content_end_date, 'y-MM-dd');
		if ($this->content_time)
			$article->content_time = Yii::$app->formatter->asTime($this->content_time, 'HH:mm');
		$article->active   = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$article->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
		if ($this->item_id) {
			ArticleCategory::deleteAll(['article_id' => $this->item_id]);
		}
		$article->tagValues = $this->tagValues;
		$article->save(false);
		if (is_array($this->categoryBoxes)) {
			foreach ( $this->categoryBoxes as $categoryBox ) {
				$articleCategory = new ArticleCategory;
				$articleCategory->article_id = $article->id;
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
		if ($article = Article::findOne($this->item_id)) {
			$article->removeAllTagValues();
			$article->delete();
		}
	}

	/**
	 * Gets category options for checkbox list
	 * @return array of pairs id - title
	 */
	public function getCategoryOptions() {
		return ArrayHelper::map(Category::find()->andWhere(['language_id' => $this->language_id])->orderBy(['title' => SORT_ASC])->all(), 'id', 'title');
	}

	/**
	 * Gets used category switches
	 * @return array
	 */
	protected function getCategoryBoxes() {
		return $this->item_id ? (ArticleCategory::find()->select('category_id')->andWhere(['article_id' => $this->item_id])->column()) : [Category::getMainCategory($this->language_id)];
	}

	/**
	 * Returns short image name
	 * @return string
	 */
	public function getImageName() {
		if (strstr($this->imageFilename, Yii::getAlias('@web') . '/' . Yii::$app->params['imageUploadDir'])) {
			$startPosition = strlen(Yii::getAlias('@web') . '/' . Yii::$app->params['imageUploadDir']);
			$filename = substr($this->imageFilename, $startPosition);
		} else {
			$filename = $this->imageFilename;
		}
		return $filename;
	}

	/**
	 * Returns layout options for dropdown
	 * @return array
	 */
	public function getLayoutListOptions() {
		return ArrayHelper::map(LayoutRecord::find()->where([
			'content' => LayoutRecord::CONTENT_ARTICLE
		])->activeStatus()->orderBy([
			'main' => SORT_DESC,
			'title' => SORT_ASC
		])->all(), 'id', 'title');
	}
}