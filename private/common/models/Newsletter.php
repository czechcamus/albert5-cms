<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 9:51
 */

namespace common\models;


use common\utilities\ModelTypeAttribute;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for content newsletter.
 */

class Newsletter extends ContentRecord
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
				'attributeValue' => self::TYPE_NEWSLETTER
			]
		];
		return ArrayHelper::merge($parentBehaviors, $thisBehaviors);
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['content_type' => self::TYPE_NEWSLETTER]);
	}
}