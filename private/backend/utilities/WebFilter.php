<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 13:00
 */

namespace backend\utilities;

use backend\controllers\MenuController;
use common\models\WebRecord;
use yii\base\ActionFilter;
use yii\base\InvalidParamException;

/**
 * Class WebFilter ensures WebRecord relation for MenuRecord
 * @package backend\utilities
 */
class WebFilter extends ActionFilter
{
	/**
	 * @param \yii\base\Action $action
	 *
	 * @return bool
	 * @throws InvalidParamException
	 */
	public function beforeAction($action)
	{
		$session = \Yii::$app->session;
		$request = \Yii::$app->request;

		if ($request->post('web_id')) {
			$id = $request->post('web_id');
			$session->set('web_id', $id);
		} elseif ($session->get('web_id')) {
			$web = WebRecord::findOne($session->get('web_id'));
			if ($web) {
				$id = $session->get('web_id');
			} else {
				$id = WebRecord::getMainWebId();
				$session->set('web_id', $id);
			}
		} else {
			$id = WebRecord::getMainWebId();
			$session->set('web_id', $id);
		}

		$session->close();
		/** @var $controller MenuController */
		$controller = $this->owner;
		$controller->setWeb($id);
		return parent::beforeAction($action);
	}
}