<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "poll_answer".
 *
 * @property integer $id
 * @property integer $poll_id
 * @property string $answer
 * @property integer $voices
 */
class PollAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'poll_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['poll_id', 'answer'], 'required'],
            [['poll_id', 'voices'], 'integer'],
            [['answer'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'poll_id' => Yii::t('app', 'Poll ID'),
            'answer' => Yii::t('app', 'Answer'),
            'voices' => Yii::t('app', 'Voices'),
        ];
    }
}
