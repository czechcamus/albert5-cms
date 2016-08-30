<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "email".
 *
 * @property integer $id
 * @property integer $language_id
 * @property string $email
 * @property string $hash
 * @property integer $active
 * @property string $created_at
 */
class EmailRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return[
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'email'], 'required'],
            [['language_id', 'active'], 'integer'],
            [['created_at'], 'safe'],
            [['email', 'hash'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'laguage_id' => Yii::t('app', 'Language'),
            'email' => Yii::t('app', 'Email'),
            'hash' => Yii::t('app', 'Hash'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

	/**
	 * Gets active emails
	 * @return array
	 */
	public static function getActiveEmails() {
		return self::find()->select('email')->where(['active' => true])->column();
    }
}
