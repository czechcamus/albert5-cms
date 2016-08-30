<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 14.2.2015
 * Time: 14:42
 */

namespace common\utilities;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class UniqueMainProperty extends Behavior
{

	/**
	 * Each element in form 1) name of attribute, 2) filter operator
	 * @var array
	 */
	public $filterAttributes = [];

	public function events()
	{
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'changeOtherMainProperties',
			ActiveRecord::EVENT_AFTER_UPDATE => 'changeOtherMainProperties'
		];
	}

	/**
	 * Switch off other main property of other records
	 * @throws \yii\db\Exception
	 */
	public function changeOtherMainProperties()
	{
		/** @var $model ActiveRecord */
		$model = $this->owner;
		$table = $model::tableName();
		if ($model->main == 1) {
			$whereString = 'id != :id';
			/** @noinspection PhpUndefinedFieldInspection */
			$paramArray = [':id' => $model->id];
			foreach ($this->filterAttributes as $filter) {
				$whereString .= ' AND ' . $filter['attribute'] . ' ' . $filter['operator'] . ' :' . $filter['attribute'];
				$paramArray[':' . $filter['attribute']] = $model->$filter['attribute'];
			}
			\Yii::$app->db->createCommand()->update($table, ['main' => 0], $whereString, $paramArray)->execute();
		}
	}
}