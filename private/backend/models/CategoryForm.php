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
use Yii;
use yii\base\Model;

class CategoryForm extends Model
{
	/** @var integer actual category id */
	public $item_id;
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
	 * @param integer|null $item_id
	 */
	public function __construct($item_id = null) {
		parent::__construct();
		if ($item_id) {
			/** @var $category Category*/
			$category = Category::findOne($item_id);
			$this->item_id = $category->id;
			$this->language_id = $category->language_id;
			$this->title = $category->title;
			$this->description = $category->description;
			$this->category_type = $category->category_type;

			if ($category->main)
				$this->boxes[] = self::PROPERTY_MAIN;
			if ($category->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
			if ($category->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
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
			['title','required', 'on' => ['create', 'update']],
			['title', 'string', 'max' => 255],
			['description', 'string'],
			[['item_id', 'language_id', 'category_type', 'boxes'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('back', 'Title'),
			'description' => Yii::t('back', 'Description'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'category_type' => Yii::t('back', 'Category Type'),
			'boxes' => Yii::t('back', 'Properties')
		];
	}

	/**
	 * Save Category model
	 * @param bool $insert
	 */
	public function saveCategory($insert = true) {
		$category = new Category();
		if ($this->item_id)
			$category->id = $this->item_id;
		$category->isNewRecord = $insert;
		$category->attributes = $this->toArray();
		$category->main   = (is_array($this->boxes) && in_array(self::PROPERTY_MAIN, $this->boxes)) ? 1 : 0;
		$category->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
		$category->active = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$category->save();
	}

	/**
	 * Deletes Category
	 * @throws \Exception
	 */
	public function deleteCategory() {
		/** @var $category Category */
		if ($category = Category::findOne($this->item_id))
			$category->delete();
	}
}