<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 13:00
 */

namespace backend\utilities;

use common\models\FileRecord;
use Yii;
use yii\base\ActionFilter;
use yii\base\InvalidParamException;

class SynchronizeFiles extends ActionFilter
{
	/** @var array types of synchronized files */
	public $syncTypes = ['images', 'files'];

	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool
	 * @throws InvalidParamException
	 */
	public function beforeAction($action)
	{
		$cookies = Yii::$app->request->cookies;
		$cookiesResponse = Yii::$app->response->cookies;
		if (in_array('images', $this->syncTypes) && $cookies->has('manageImagesTime')) {
			$model = new FileRecord;
			if ($cookies->has('manageImagesTime')) {
				$model->synchronizeImages();
				if ($cookies->getValue('manageImagesTime') < (time() - 300)) {
					$cookiesResponse->remove('manageImagesTime');
				}
				$model->checkDB(Yii::$app->params['imageUploadDir'], FileRecord::TYPE_IMAGE);
			}
		}
		if (in_array('files', $this->syncTypes) && $cookies->has('manageFilesTime')) {
			$model = new FileRecord;
			if ($cookies->has('manageFilesTime')) {
				$model->synchronizeFiles();
				if ($cookies->getValue('manageFilesTime') < (time() - 300)) {
					$cookiesResponse->remove('manageFilesTime');
				}
				$model->checkDB(Yii::$app->params['fileUploadDir'], FileRecord::TYPE_FILE);
			}
		}

		return parent::beforeAction($action);
	}
}