<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 8:58
 */

namespace common\utilities;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class ModelTypeAttribute extends Behavior 
{
	/**
	 * @var $attribute string name of attribute
	 */
	public $attribute;

	/**
	 * @var $attributeValue mixed value of attribute
	 */
	public $attributeValue;

	/**
	 * @return array events
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'setModelType'
		];
	}

	/**
	 * Sets given value of given attribute
	 */
	public function setModelType() {
		/** @var $model ActiveRecord */
		$model = $this->owner;
		$model->{$this->attribute} = $this->attributeValue;
	}
}