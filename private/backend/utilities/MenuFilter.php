<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 13:00
 */

namespace backend\utilities;

use common\models\MenuRecord;
use yii\base\ActionFilter;
use yii\base\InvalidParamException;

class MenuFilter extends ActionFilter
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
			$session->set('web_id', $request->post('web_id'));
			$id = MenuRecord::getMainMenuId();
			$session->set('menu_id', $id);
		} else {
			if ($request->post('menu_id')) {
				$id = $request->post( 'menu_id' );
				$session->set( 'menu_id', $id );
			} elseif ($session->get('menu_id')) {
				$menu = MenuRecord::findOne($session->get('menu_id'));
				if ($menu) {
					$id = $session->get('menu_id');
				} else {
					$id = MenuRecord::getMainMenuId();
					$session->set('menu_id', $id);
				}
			} else {
				$id = MenuRecord::getMainMenuId();
				$session->set('menu_id', $id);
			}
		}

		$session->close();
		/* @var $controller \backend\controllers\MenuItemController */
		$controller = $this->owner;
		$controller->setMenu($id);
		return parent::beforeAction($action);
	}
}