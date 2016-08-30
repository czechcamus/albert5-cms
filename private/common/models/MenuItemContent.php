<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_item_content".
 *
 * @property integer $menu_item_id
 * @property integer $content_id
 * @property integer $category_id
 */
class MenuItemContent extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_item_id'], 'required'],
            [['menu_item_id', 'content_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_item_id' => Yii::t('app', 'Menu Item'),
            'content_id' => Yii::t('app', 'Content'),
            'category_id' => Yii::t('app', 'Category'),
        ];
    }
}
