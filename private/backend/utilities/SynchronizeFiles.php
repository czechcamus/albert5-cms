<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 13:00
 */

namespace backend\utilities;

use common\models\FileRecord;
use yii\base\ActionFilter;
use yii\base\InvalidParamException;

class SynchronizeFiles extends ActionFilter
{
	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool
	 * @throws InvalidParamException
	 */
	public function beforeAction($action)
	{
		$model = new FileRecord;
		$images = $model->synchronizeImages();
		$files = $model->synchronizeFiles();
		$model->checkDB(array_merge($images, $files));
		return parent::beforeAction($action);
	}
}