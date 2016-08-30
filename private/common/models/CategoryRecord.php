<?php

namespace common\models;

use common\utilities\StatusQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 * @property integer $category_type
 * @property integer $main
 * @property integer $public
 * @property integer $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property UserRecord $createdBy
 * @property LanguageRecord $language
 * @property UserRecord $updatedBy
 */
class CategoryRecord extends ActiveRecord
{

    const TYPE_CATEGORY = 1;
    const TYPE_GALLERY = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()'),
			],
			'blame' => BlameableBehavior::className()
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'title'], 'required'],
            [['language_id', 'category_type', 'main', 'public', 'active', 'created_by', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
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
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'category_type' => Yii::t('app', 'Category Type'),
            'main' => Yii::t('app', 'Main'),
            'public' => Yii::t('app', 'Public'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

	/**
	 * @inheritdoc
	 * @return StatusQuery
	 */
	public static function find()
	{
		return new StatusQuery(get_called_class());
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserRecord::className(), ['id' => 'created_by']);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(UserRecord::className(), ['id' => 'updated_by']);
    }
}
