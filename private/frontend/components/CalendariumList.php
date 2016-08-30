<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 2.9.2015
 * Time: 19:32
 */

namespace frontend\components;

use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\base\InvalidParamException;

/**
 * Class CalendariumList displays list of calendarium items
 * @package frontend\components\homepage
 */
class CalendariumList extends CategoryArticlesList
{
	public function init() {
		parent::init();
		if ($this->categoryId) {
			$language_id = FrontEndHelper::getLanguageIdFromAcronym();
			$web_id = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));

			$this->viewName = 'calendarium';
			$this->orderBy = 'DATE_FORMAT(content.content_date, "%m%d") ASC';
			$this->withImage = true;
			$this->withDate = true;

			$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
					INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
					INNER JOIN `menu` ON menu.id=menu_item.menu_id
					WHERE ((((((`content`.`content_type`=:content_type))
						AND (NOT (`content`.`id`" . ($this->articleId ? "=" . $this->articleId : " IS NULL") . ")))
                        AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
						AND (`menu`.`web_id`=:web_id))
					ORDER BY " . $this->orderBy  . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

			$query = ContentRecord::findBySql($sql, [
				':category_id' => $this->categoryId ? : $this->categoryId,
				':content_type' => ContentRecord::TYPE_ARTICLE,
				':language_id' => $language_id,
				':web_id' => $web_id
			]);

			$this->_items = $query->all();

			$this->setMenuUrlParts();
		} else {
			throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - categoryId');
		}
	}
}