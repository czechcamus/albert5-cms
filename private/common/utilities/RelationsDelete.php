<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.6.2015
 * Time: 21:49
 */

namespace common\utilities;


use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class RelationsDelete deletes relation records before deleting of actual model
 * @package common\utilities
 */
class RelationsDelete extends Behavior
{
	public $relations = [];

	/**
	 * @return array
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_DELETE => 'deleteRelations'
		];
	}

	/**
	 * Deletes relations
	 */
	public function deleteRelations() {
		$model = $this->owner;
		//foreach ( $this->relations as $key => $relation ) {
		foreach ( $this->relations as $relation ) {
			if (is_array($model->$relation)) {
				foreach ( $model->$relation as $relationItem ) {
					/** @var $relationItem ActiveRecord */
					$relationItem->delete();
				}
			} elseif ($model->$relation) {
				$model->$relation->delete();
			}
		}
	}
}