<?php

namespace common\models;

use common\utilities\RelationsDelete;
use common\utilities\StatusQuery;
use common\utilities\UniqueMainProperty;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "poll".
 *
 * @property integer $id
 * @property integer $language_id
 * @property string $question
 * @property integer $main
 * @property integer $active
 * @property string $end_date
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property PollAnswerRecord $answers
 * @property integer $totalVoices
 */
class PollRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'main' => UniqueMainProperty::className(),
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
            'blame' => BlameableBehavior::className(),
            'relationDelete' => [
                'class' => RelationsDelete::className(),
                'relations' => ['answers']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'question'], 'required'],
            [['language_id', 'active', 'main', 'created_by', 'updated_by'], 'integer'],
            [['end_date'], 'default', 'value' => null],
            [['end_date'], 'date', 'format' => 'y-MM-dd'],
            [['created_at', 'updated_at'], 'safe'],
            [['question'], 'string', 'max' => 255]
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
            'question' => Yii::t('app', 'Question'),
            'active' => Yii::t('app', 'Active'),
            'main' => Yii::t('app', 'Main'),
            'end_date' => Yii::t('app', 'End date'),
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
	public function getAnswers() {
		return $this->hasMany(PollAnswerRecord::className(), ['poll_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTotalVoices() {
		return $this->hasMany(PollAnswerRecord::className(), ['poll_id' => 'id'])->sum('voices');
	}

	/**
	 * Gets ID of main poll
	 * @return bool|null|string
	 */
	public static function getMainPollId() {
		return Yii::$app->db->createCommand('SELECT id FROM poll WHERE main = 1')->queryScalar();
	}

}
