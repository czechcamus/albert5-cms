<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 9:51
 */

namespace common\models;


use common\utilities\MenuItemsReset;
use common\utilities\ModelTypeAttribute;
use common\utilities\RelationsDelete;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for content page.
 *
 * @property MenuItemContent[] $menuItemContents
 * @property PageFieldRecord[] $pageFields
 */

class Page extends ContentRecord
{
	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors() {
		$parentBehaviors = parent::behaviors();
		$thisBehaviors = [
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'content_type',
				'attributeValue' => self::TYPE_PAGE
			],
			'menuItemsReset' => MenuItemsReset::className(),
			'relationDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['menuItemContents']
			]
		];
		return ArrayHelper::merge($parentBehaviors, $thisBehaviors);
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['content_type' => self::TYPE_PAGE]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMenuItemContents() {
		return $this->hasMany(MenuItemContent::className(), ['content_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPageFields() {
		return $this->hasMany(PageFieldRecord::className(), ['page_id' => 'id']);
	}
}