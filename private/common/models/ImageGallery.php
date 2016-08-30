<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "image_gallery".
 *
 * @property integer id
 * @property integer $image_id
 * @property integer $gallery_id
 * @property integer $item_order
 *
 * @property Gallery $gallery
 * @property Image $image
 */
class ImageGallery extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'gallery_id'], 'required'],
            [['image_id', 'gallery_id', 'item_order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => Yii::t('app', 'Image'),
            'gallery_id' => Yii::t('app', 'Gallery'),
            'item_order' => Yii::t('app', 'Items order'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }
}
