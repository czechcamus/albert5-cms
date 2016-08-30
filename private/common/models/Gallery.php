<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.2.2015
 * Time: 9:35
 */

namespace common\models;


use common\utilities\ModelTypeAttribute;
use common\utilities\RelationsDelete;
use yii\helpers\ArrayHelper;

/**
 * Class Gallery
 * @package common\models
 *
 * @property ImageGallery[] $imageGalleries
 * @property Image[] $images
 */
class Gallery extends CategoryRecord
{
	const DISPLAY_BOX = 'box';
	const DISPLAY_BAR = 'bar';

	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors()
	{
		$parentBehaviors = parent::behaviors();
		$thisBehaviors = [
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'category_type',
				'attributeValue' => self::TYPE_GALLERY
			],
			'relationDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['imageGalleries']
			]
		];
		return ArrayHelper::merge($parentBehaviors, $thisBehaviors);
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['category_type' => self::TYPE_GALLERY]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImageGalleries()
	{
		return $this->hasMany(ImageGallery::className(), ['gallery_id' => 'id'])->orderBy('item_order');
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImages()
	{
		return $this->hasMany(Image::className(), ['id' => 'image_id'])->via('imageGalleries');
	}

	/**
	 * Gets gallery display types
	 * @return array
	 */
	public static function getDisplayTypes()
	{
		return [self::DISPLAY_BAR, self::DISPLAY_BOX];
	}
}