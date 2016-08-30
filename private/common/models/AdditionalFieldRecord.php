<?php

namespace common\models;

use common\utilities\RelationsDelete;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "additional_field".
 *
 * @property integer $id
 * @property integer $language_id
 * @property string $label
 *
 * @property LanguageRecord $language
 * @property PageFieldRecord[] $pageFields
 */
class AdditionalFieldRecord extends ActiveRecord
{
    public function behaviors() {
        return [
	        'relationDelete' => [
		    'class' => RelationsDelete::className(),
		    'relations' => ['pageFields']
	    ]];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'additional_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id'], 'integer'],
            [['label'], 'string', 'max' => 255],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => LanguageRecord::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'language_id' => Yii::t('app', 'Language ID'),
            'label' => Yii::t('app', 'Field label'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(LanguageRecord::className(), ['id' => 'language_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageFields()
    {
        return $this->hasMany(PageFieldRecord::className(), ['additional_field_id' => 'id']);
    }
}
