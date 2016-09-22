<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 6.7.2015
 * Time: 14:55
 */

namespace common\models;


use common\utilities\ModelTypeAttribute;
use common\utilities\RelationsDelete;

/**
 * Class Image
 * @package common\models
 *
 * @property Article[] $articles
 * @property Category[] $categories
 * @property ImageGallery[] $imageGalleries
 */
class Image extends FileRecord
{
	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors()
	{
		return [
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'type',
				'attributeValue' => self::TYPE_IMAGE
			],
			'relationDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['imageGalleries']
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['type' => self::TYPE_IMAGE]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticles()
	{
		return $this->hasMany(Article::className(), ['image_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategories()
	{
		return $this->hasMany(Category::className(), ['image_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImageGalleries()
	{
		return $this->hasMany(ImageGallery::className(), ['image_id' => 'id']);
	}
}