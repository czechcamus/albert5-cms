<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 6.7.2015
 * Time: 23:56
 */

namespace common\utilities;


use common\models\FileRecord;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class FileDelete extends Behavior
{
	/**
	 * @return array
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_DELETE => 'deleteFile'
		];
	}

	/**
	 * Deletes file on the server
	 */
	public function deleteFile() {
		/** @var $model FileRecord */
		$model = $this->owner;
		@unlink(\Yii::$app->params['fileUploadDir'] . $model->filename);
	}
}