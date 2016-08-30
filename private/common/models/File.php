<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 6.7.2015
 * Time: 14:55
 */

namespace common\models;


use common\utilities\ModelTypeAttribute;

class File extends FileRecord
{
	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors()
	{
		return [
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'type',
				'attributeValue' => self::TYPE_FILE
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['type' => self::TYPE_FILE]);
	}
}