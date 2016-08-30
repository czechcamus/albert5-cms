<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 19.8.2015
 * Time: 13:43
 */

namespace frontend\utilities;


use frontend\controllers\PageController;
use yii\base\ActionFilter;

/**
 * Class MenuFilter checks existence of menu item ID
 * @package frontend\utilities
 */
class MenuFilter extends ActionFilter
{
	/**
	 * @inheritdoc
	 */
	public function beforeAction( $action ) {
		/** @var PageController $controller */
		$controller = $this->owner;

		$menuItemId = \Yii::$app->request->get('id');
		if (!isset($menuItemId)) {
			$menuItemId = FrontEndHelper::getMainMenuItemId($controller->web->id, $controller->language->id);
		}

		$controller->setMenuContent($menuItemId);

		return parent::beforeAction($action);
	}
}