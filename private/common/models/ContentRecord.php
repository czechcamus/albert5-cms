<?php

namespace common\models;

use common\utilities\StatusQuery;
use common\utilities\TaggableExtendedBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property integer $language_id
 * @property integer $image_id
 * @property string $perex
 * @property string $title
 * @property string $description
 * @property string $content_date
 * @property string $content_time
 * @property string $content_end_date
 * @property string $content_end_time
 * @property integer $content_type
 * @property integer $active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property integer $public
 * @property integer $layout_id
 * @property integer $order_time
 * @property string tagValues
 *
 * @property Image $image
 * @property UserRecord $createdBy
 * @property LanguageRecord $language
 * @property UserRecord $updatedBy
 * @property LayoutRecord $layout
 * @property TagRecord $tags
 * @property ContentFieldRecord[] $contentFields
 */
class ContentRecord extends ActiveRecord
{
    const TYPE_ARTICLE = 1;
    const TYPE_PAGE = 2;
	const TYPE_NEWSLETTER = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content';
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
			'blame' => BlameableBehavior::className(),
			'taggable' => TaggableExtendedBehavior::className()
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'title'], 'required'],
            [['image_id', 'language_id', 'content_type', 'active', 'created_by', 'updated_by', 'public', 'layout_id'], 'integer'],
            [['perex', 'description'], 'string'],
	        [['content_date', 'content_time', 'content_end_date', 'content_end_time'], 'default', 'value' => null],
	        [['content_date', 'content_end_date'], 'date', 'format' => 'y-MM-dd'],
	        [['content_time', 'content_end_time'], 'date', 'format' => 'HH.mm'],
	        [['order_time'], 'date', 'format' => 'y-MM-dd HH:mm'],
            [['created_at', 'updated_at', 'image_id', 'layout_id', 'tagValues'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

	public function transactions() {
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL
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
            'image_id' => Yii::t('app', 'Image'),
            'perex' => Yii::t('app', 'Perex'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
	        'content_date' => Yii::t('app', 'Date'),
	        'content_time' => Yii::t('app', 'Time'),
            'content_end_date' => Yii::t('app', 'End date'),
            'content_end_time' => Yii::t('app', 'End time'),
            'content_type' => Yii::t('app', 'Content Type'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'public' => Yii::t('app', 'Public'),
            'layout_id' => Yii::t('app', 'Layout'),
            'order_time' => Yii::t('app', 'Time stamp for order'),
            'imageFilename' => Yii::t('app', 'Image filename'),
	        'tagValues' => Yii::t('app', 'Tags')
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
	 * Gets number of records of given type
	 * @param int $contentType
	 * @return int|string
	 */
	public static function getRecordsCount( $contentType = self::TYPE_ARTICLE ) {
		$query = self::find()->where(['content_type' => $contentType]);
		if (!Yii::$app->user->can('manager')) {
			$query->andWhere(['created_by' => Yii::$app->user->id]);
		}
		return $query->count();
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage() {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayout() {
        return $this->hasOne(LayoutRecord::className(), ['id' => 'layout_id']);
    }

	/**
	 * @return ActiveQuery
	 */
	public function getTags() {
		return $this->hasMany(TagRecord::className(), ['id' => 'tag_id'])->viaTable('content_tag', ['content_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContentFields() {
		return $this->hasMany(ContentFieldRecord::className(), ['content_id' => 'id']);
	}
}
