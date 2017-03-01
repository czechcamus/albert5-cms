<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 18.2.2015
 * Time: 14:59
 */

namespace backend\models;


use common\models\Gallery;
use common\models\LanguageRecord;
use Yii;
use yii\base\Model;

class GalleryForm extends Model
{
	/** @var integer actual gallery id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var string title of gallery */
	public $title;
	/** @var string description of gallery */
	public $description;
	/** @var integer category type */
	public $category_type;
	/** @var array boxes of properties */
	public $boxes;

	const PROPERTY_PUBLIC = 1;
	const PROPERTY_ACTIVE = 2;

	/**
	 * GalleryForm constructor
	 *
	 * @param integer|null $item_id
	 * @param bool $copy
	 */
	public function __construct($item_id = null, $copy = false) {
		parent::__construct();
		if ($item_id) {
			/** @var $gallery Gallery */
			$gallery = Gallery::findOne($item_id);
			if ( ! $copy ) {
				$this->item_id = $gallery->id;
			}
			$this->language_id = $gallery->language_id;
			$this->title = $gallery->title;
			$this->description = $gallery->description;
			$this->category_type = $gallery->category_type;

			if ($gallery->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
			if ($gallery->active)
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
	 * Save Gallery model
	 * @param bool $insert
	 */
	public function saveGallery($insert = true) {
		$gallery = $insert === true ? new Gallery : Gallery::findOne($this->item_id);
		if ($this->item_id)
			$gallery->id = $this->item_id;
		$gallery->attributes = $this->toArray();
		$gallery->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
		$gallery->active = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$gallery->save();
	}

	/**
	 * Deletes Gallery
	 * @throws \Exception
	 */
	public function deleteGallery() {
		/** @var $gallery Gallery */
		if ($gallery = Gallery::findOne($this->item_id))
			$gallery->delete();
	}
}