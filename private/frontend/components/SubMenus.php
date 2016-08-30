<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 11.9.2015
 * Time: 22:42
 */

namespace frontend\components;


use frontend\models\MenuContent;
use yii\base\InvalidParamException;
use yii\bootstrap\Widget;

/**
 * Class SubMenus displays submenus items list
 * @property $parentMenuItemId id of parent menu item - required
 * @property $viewName name of view file
 * @property $title title of menubox
 * @package frontend\components
 */
class SubMenus extends Widget
{
	/** @var int parent menu item id */
	public $parentMenuItemId;

	/** @var string name of view file */
	public $viewName = 'menuBox';

	/** @var string title of menubox */
	public $title = '';

	private $_items;

	public function init() {
		parent::init();
		if($this->parentMenuItemId) {
			$query = MenuContent::find()->where(['and', 'active=1', 'parent_id=' . $this->parentMenuItemId]);
			if (\Yii::$app->user->isGuest) {
				$query->andWhere([
					'public' => 1
				]);
			}
			$this->_items = $query->orderBy(['item_order' => SORT_ASC])->all();
		} else {
			throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - parentMenuItemId');
		}
	}

	public function run() {
		return $this->render($this->viewName, [
			'items' => $this->_items,
			'title' => $this->title
		]);
	}
}