<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 15.6.2016
 * Time: 14:10
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "page_field
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $additional_field_id
 * @property string $content
 *
 * @property Page $page 
 * @property AdditionalFieldRecord $additionalField
 */
class PageFieldRecord extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'page_field';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['page_id', 'additional_field_id'], 'required'],
			[['page_id', 'additional_field_id'], 'integer'],
			[['content'], 'string', 'max' => 255]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'page_id' => Yii::t('app', 'Page ID'),
			'additional_field_id' => Yii::t('app', 'Additional field ID'),
			'content' => Yii::t('app', 'Field content'),
			'page' => Yii::t('app', 'Page')
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPage() {
		return $this->hasOne(Page::className(), ['id' => 'page_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdditionalField() {
		return $this->hasOne(AdditionalFieldRecord::className(), ['id' => 'additional_field_id']);
	}
}