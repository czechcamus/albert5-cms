<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 14.2.2015
 * Time: 14:42
 */

namespace common\utilities;


use common\models\LayoutRecord;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\db\ActiveRecord;

class UpdateLayoutId extends Behavior
{

	/**
	 * @return array
	 */
	public function events()
	{
		return [
			ActiveRecord::EVENT_BEFORE_DELETE => 'changeContentLayoutId'
		];
	}

	/**
	 * Changes layout_id to main layout_id before deleting this layout_id
	 * @throws \yii\db\Exception
	 */
	public function changeContentLayoutId()
	{
		/** @var $model LayoutRecord */
		$model = $this->owner;
		if ($main_id = LayoutRecord::getMainLayoutId($model->content)) {
			\Yii::$app->db->createCommand()->update('menu_item', ['layout_id' => $main_id], ['layout_id' => $model->id])->execute();
			if ($model->content == 1) {
				\Yii::$app->db->createCommand()->update('content', ['layout_id' => $main_id], ['layout_id' => $model->id])->execute();
			}
		} else {
			throw new ErrorException(\Yii::t('app', 'Main layout not exists!'));
		}
	}
}