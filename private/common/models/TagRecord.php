<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tag".
 *
 * @property integer $id
 * @property integer $frequency
 * @property string $name
 *
 * @property ContentRecord[] $contents
 */
class TagRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['frequency'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'frequency' => Yii::t('app', 'Frequency'),
            'name' => Yii::t('app', 'Name'),
        ];
    }
}
