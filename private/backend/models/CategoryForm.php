<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 18.2.2015
 * Time: 14:59
 */

namespace backend\models;


use common\models\Category;
use common\models\LanguageRecord;
use common\models\MenuItemRecord;
use yii\helpers\Url;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CategoryForm extends Model {
	/** @var integer actual category id */
	public $item_id;
	/** @var integer Id of parent category */
	public $parent_id;
	/** @var integer language id */
	public $language_id;
	/** @var string title of category */
	public $title;
	/** @var string description of category */
	public $description;
	/** @var integer category type */
	public $category_type;
	/** @var array boxes of properties */
	public $boxes;

	const PROPERTY_MAIN = 1;
	const PROPERTY_PUBLIC = 2;
	const PROPERTY_ACTIVE = 3;

	/**
	 * CategoryForm constructor
	 *
	 * @param integer|null $item_id
	 * @param bool $copy
	 */
	public function __construct( $item_id = null, $copy = false ) {
		parent::__construct();
		if ( $item_id ) {
			/** @var $category Category */
			$category            = Category::findOne( $item_id );
			if ( ! $copy ) {
				$this->item_id = $category->id;
			}
			$this->parent_id     = $category->parent_id;
			$this->language_id   = $category->language_id;
			$this->title         = $category->title;
			$this->description   = $category->description;
			$this->category_type = $category->category_type;

			if ( $category->main ) {
				$this->boxes[] = self::PROPERTY_MAIN;
			}
			if ( $category->public ) {
				$this->boxes[] = self::PROPERTY_PUBLIC;
			}
			if ( $category->active ) {
				$this->boxes[] = self::PROPERTY_ACTIVE;
			}
		} else {
			$this->parent_id = null;
			$session         = Yii::$app->session;
			if ( ! $session['language_id'] ) {
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			}
			$this->language_id = $session['language_id'];

			$this->boxes[] = self::PROPERTY_PUBLIC;
			$this->boxes[] = self::PROPERTY_ACTIVE;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[ 'title', 'required', 'on' => [ 'create', 'update' ] ],
			[ 'title', 'string', 'max' => 255 ],
			[ 'description', 'string' ],
			[ [ 'parent_id', 'item_id', 'language_id', 'category_type', 'boxes' ], 'safe' ]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'title'         => Yii::t( 'back', 'Title' ),
			'description'   => Yii::t( 'back', 'Description' ),
			'parent_id'     => Yii::t( 'back', 'Parent Category' ),
			'item_id'       => Yii::t( 'back', 'Actual Item' ),
			'language_id'   => Yii::t( 'back', 'Language' ),
			'category_type' => Yii::t( 'back', 'Category Type' ),
			'boxes'         => Yii::t( 'back', 'Properties' )
		];
	}

	/**
	 * Save Category model
	 *
	 * @param bool $insert
	 */
	public function saveCategory( $insert = true ) {
		$category = $insert === true ? new Category : Category::findOne($this->item_id);
		if ( $this->item_id ) {
			$category->id = $this->item_id;
		}
		$category->attributes  = $this->toArray();
		$category->main        = ( is_array( $this->boxes ) && in_array( self::PROPERTY_MAIN, $this->boxes ) ) ? 1 : 0;
		$category->public      = ( is_array( $this->boxes ) && in_array( self::PROPERTY_PUBLIC,
				$this->boxes ) ) ? 1 : 0;
		$category->active      = ( is_array( $this->boxes ) && in_array( self::PROPERTY_ACTIVE,
				$this->boxes ) ) ? 1 : 0;
		$category->save();
	}

	/**
	 * Deletes Category
	 * @throws \Exception
	 */
	public function deleteCategory() {
		/** @var $category Category */
		if ( $category = Category::findOne( $this->item_id ) ) {
			$category->delete();
		}
	}

	/**
	 * Gets parent categories tree for dropdown
	 *
	 * @param integer $pid
	 * @param integer $i
	 *
	 * @return array
	 */
	public function getParentCategories( $pid = null, $i = 0 ) {
		$listItems = [];
		/** @noinspection PhpUndefinedMethodInspection */
		$query = Category::find()->andWhere( 'main = 0 AND language_id = :lid',
			[ ':lid' => $this->language_id ] );
		if ( $pid === null ) {
			$query->andWhere( 'ISNULL(parent_id)' );
		} else {
			$query->andWhere( 'parent_id = :pid', [ ':pid' => $pid ] );
		}
		if ( $this->scenario == 'update' ) {
			$query->andWhere( 'id != :id', [ ':id' => $this->item_id ] );
		}
		$items = $query->all();
		foreach ( $items as $item ) {
			/** @var $item \common\models\Category */
			$listItems[ $item->id ] = str_repeat( '=', $i ) . ( $i > 0 ? ' ' : '' ) . $item->title;
			if ( $item->hasItems() ) {
				$listItems = ArrayHelper::merge( $listItems, $this->getParentCategories( $item->id, ++ $i ) );
				-- $i;
			}
		}

		return $listItems;
	}

	/**
	 * Returns categories list
	 *
	 * @param $lid
	 * @param null $pid
	 * @param int $i
	 *
	 * @return array
	 */
	public static function getCategoriesList( $lid, $pid = null, $i = 0 ) {
		$listItems = [];
		if ( isset( $lid ) ) {
			/** @noinspection PhpUndefinedMethodInspection */
			$query = Category::find()->andWhere( 'language_id = :lid', [ ':lid' => $lid ] );
			if ( $pid === null ) {
				$query->andWhere( 'ISNULL(parent_id)' );
			} else {
				$query->andWhere( 'parent_id = :pid', [ ':pid' => $pid ] );
			}
			/** @noinspection PhpUndefinedMethodInspection */
			$items = $query->orderBy( [ 'main' => SORT_DESC, 'title' => SORT_ASC ] )->all();
			foreach ( $items as $item ) {
				/** @var $item \common\models\Category */
				$listItems[] = [
					'title'         => ($i > 0 ? '<span style="color: lightgrey">' : '') . str_repeat( '&mdash;', $i ) . ($i > 0 ? '</span>' : '') . ( $i > 0 ? ' ' : '' ) . $item->title,
					'main'          => Yii::t( 'back', $item->main ? 'yes' : 'no' ),
					'public'        => Yii::t( 'back', $item->public ? 'yes' : 'no' ),
					'active'        => Yii::t( 'back', $item->active ? 'yes' : 'no' ),
					'articlesCount' => $item->articlesCount,
					'buttons'       => '<span class="show-loading">' .
	                    Html::a( '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', ['category/update', 'id' => $item->id],
							[
								'title' => Yii::t( 'back', 'Update category' ),
								'class' => 'btn btn-link',
								'style' => 'padding: 0 3px 0 0'
							] ) . '</span><span class="show-loading">' .
                        Html::a( '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>', ['category/copy', 'id' => $item->id],
                           [
                               'title' => Yii::t( 'back', 'Copy category' ),
                               'class' => 'btn btn-link',
                               'style' => 'padding: 0'
                           ] ) . '</span>' . ( !$item->main && $item->isDeletable() ? ' ' .
						Html::a('<span class="glyphicon glyphicon-trash"></span>', ['category/delete', 'id' => $item->id], [
							'title' => Yii::t('back', 'Delete category'),
							'data-confirm' => Yii::t('back', 'Are you sure you want to delete this category?'),
							'data-method' => 'post'
						]) : '') . (Yii::$app->user->can('manager') ? ' ' .
						Html::button('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>', [
							'value' => Url::to([
								'menu-item/create-from-content',
								'content_type' => MenuItemRecord::CONTENT_CATEGORY,
								'content_id' => $item->id
							]),
							'title' => Yii::t('back', 'Create menu item from category'),
							'class' => 'showModalButton btn btn-link',
							'style' => 'padding: 0'
						]) : '') . '<span class="show-loading">' .
                        Html::a('<span class="glyphicon glyphicon-list" aria-hidden="true"></span>', [
	                       'articles',
	                       'id' => $item->id
                        ],
                        [
	                       'title' => Yii::t('back', 'List of Articles'),
	                       'class' => 'btn btn-link',
	                       'style' => 'padding: 0'
                        ]) . '</span>'
				];
				if ( $item->hasItems() ) {
					$listItems = ArrayHelper::merge( $listItems, self::getCategoriesList( $lid, $item->id, ++ $i ) );
					-- $i;
				}
			}
		} else {
			throw new InvalidParamException( Yii::t( 'back', 'No language ID given!' ) );
		}

		return $listItems;
	}
}