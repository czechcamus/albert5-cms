<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 9.9.2015
 * Time: 9:00
 */

namespace common\utilities;


use common\models\Category;
use common\models\MenuItemRecord;
use common\models\Page;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class MenuItemsReset resets menu item before deleting relationed model
 * @package common\utilities
 */
class MenuItemsReset extends Behavior
{
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_DELETE => 'menuItemsReset'
		];
	}

	public function menuItemsReset() {
		/** @var Page|Category $model */
		$model = $this->owner;
		if ($model->menuItemContents) {
			foreach ( $model->menuItemContents as $menuItemContent ) {
				/** @var MenuItemRecord $menuItem */
				$menuItem = MenuItemRecord::findOne($menuItemContent->menu_item_id);
				$menuItem->layout_id = null;
				$menuItem->link_url = '#';
				$menuItem->content_type = MenuItemRecord::CONTENT_LINK;
				$menuItem->save(false);
			}
		}
	}
}