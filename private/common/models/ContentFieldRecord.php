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
 * This is the model class for table content_field
 *
 * @property integer $id
 * @property integer $content_id
 * @property integer $additional_field_id
 * @property string $content
 *
 * @property ContentRecord $relatedContent
 * @property AdditionalFieldRecord $additionalField
 */
class ContentFieldRecord extends ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'content_field';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['content_id', 'additional_field_id'], 'required'],
			[['content_id', 'additional_field_id'], 'integer'],
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
			'content_id' => Yii::t('app', 'Related content ID'),
			'additional_field_id' => Yii::t('app', 'Additional field ID'),
			'content' => Yii::t('app', 'Field content'),
			'relatedContent' => Yii::t('app', 'Related content')
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getRelatedContent() {
		return $this->hasOne(ContentRecord::className(), ['id' => 'content_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdditionalField() {
		return $this->hasOne(AdditionalFieldRecord::className(), ['id' => 'additional_field_id']);
	}
}