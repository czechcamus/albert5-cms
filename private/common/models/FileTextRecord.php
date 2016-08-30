<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "file_text".
 *
 * @property integer $id
 * @property integer $file_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 *
 * @property LanguageRecord $language
 * @property FileRecord $file
 */
class FileTextRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'file_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_id', 'language_id'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title', 'description'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file_id' => Yii::t('app', 'File'),
            'language_id' => Yii::t('app', 'Language'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
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
    public function getFile()
    {
        return $this->hasOne(FileRecord::className(), ['id' => 'file_id']);
    }
}
