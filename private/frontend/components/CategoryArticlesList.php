<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 11.9.2015
 * Time: 22:42
 */

namespace frontend\components;


use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class CategoryArticlesList displays category articles list
 * @package frontend\components
 */
class CategoryArticlesList extends Widget
{
	/** @var int category id */
	public $categoryId;

	/** @var integer menu items layout id for getting right menu items id */
	public $layoutId = null;

	/** @var int not displayed article id */
	public $articleId = null;

	/** @var int number of items */
	public $itemsCount = null;

	/** @var int number of columns */
	public $columnsCount = 1;

	/** @var string $title of list */
	public $title = null;

	/** @var bool $checkDate will we check date of article? */
	public $checkDate = false;

	/** @var string order by expression */
	public $orderBy = 'content.updated_at DESC';

	/** @var int perex words count  */
	public $wordsCount = null;

	/** @var bool is perex with image? */
	public $withImage = false;

	/** @var bool is date info? */
	public $withDate = false;

	/** @var int max image width */
	public $maxImageWidth;

	/** @var int image edge ratio */
	public $imageEdgeRatio = 1;

	/** @var bool if no data message will be displayed */
	public $noMessage = false;

	/** @var string name of view file */
	public $viewName = 'simpleTitleList';

	protected $_items;

	protected $_menuUrlParts = [];

	public function init() {
		parent::init();
		if ($this->categoryId) {
			$language_id = FrontEndHelper::getLanguageIdFromAcronym();
			$web_id = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));

			if ($this->checkDate === true) {

				$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
					INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
					INNER JOIN `menu` ON menu.id=menu_item.menu_id
					WHERE (((((((`content`.`content_type`=:content_type))
						AND ((content.content_date>=:actual_date) OR (content.content_end_date>=:actual_date)))
						AND (NOT (`content`.`id`" . ($this->articleId ? "=" . $this->articleId : " IS NULL") . ")))
						AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
						AND (`menu`.`web_id`=:web_id))
					ORDER BY " . $this->orderBy . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

				$query = ContentRecord::findBySql($sql, [
					':category_id' => $this->categoryId,
					':content_type' => ContentRecord::TYPE_ARTICLE,
					':language_id' => $language_id,
					':web_id' => $web_id,
					':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
				]);

			} else {

				$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
					INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
					INNER JOIN `menu` ON menu.id=menu_item.menu_id
					WHERE ((((((((`content`.`content_type`=:content_type))
						AND (NOT (`content`.`id`" . ($this->articleId ? "=" . $this->articleId : " IS NULL") . ")))
						AND (IF(`content`.`content_date` IS NULL, `content`.`content_date` IS NULL, `content`.`content_date`<=:actual_date)))
                        AND (IF(`content`.`content_end_date` IS NULL, `content`.`content_end_date` IS NULL, `content`.`content_end_date`>=:actual_date)))
                        AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
						AND (`menu`.`web_id`=:web_id))
					ORDER BY " . $this->orderBy  . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

				$query = ContentRecord::findBySql($sql, [
					':category_id' => $this->categoryId,
					':content_type' => ContentRecord::TYPE_ARTICLE,
					':language_id' => $language_id,
					':web_id' => $web_id,
					':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
				]);

			}
			$this->_items = $query->all();

			$this->setMenuUrlParts();
		} else {
			throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - categoryId');
		}
	}

	public function run() {
		$config = [
			'items' => $this->_items,
			'menuUrlParts' => $this->_menuUrlParts,
			'columnsCount' => $this->columnsCount,
			'title' => $this->title,
			'wordsCount' => $this->wordsCount,
			'withImage' => $this->withImage,
			'withDate' => $this->withDate,
			'noMessage' => $this->noMessage
		];
		if ($this->withImage) {
			$config = ArrayHelper::merge($config, [
				'maxImageSize' => [
					'width' => $this->maxImageWidth,
					'height' => $this->getImageHeight()
				]
			]);
		}
		return $this->render($this->viewName, $config);
	}

	public function setMenuUrlParts() {
		if ($this->layoutId) {
			$sql = "SELECT menu_item.id,menu_item.title AS name FROM category, menu_item_content, menu_item WHERE category.id=:id AND category.id=menu_item_content.category_id AND menu_item_content.menu_item_id=menu_item.id AND menu_item.layout_id=:layout_id";
		} else {
			$sql = "SELECT menu_item.id,menu_item.title AS name FROM category, menu_item_content, menu_item WHERE category.id=:id AND category.id=menu_item_content.category_id AND menu_item_content.menu_item_id=menu_item.id";
		}
		$query = \Yii::$app->db->createCommand($sql);
		$query->bindValue(':id', $this->categoryId);
		if ($this->layoutId) {
			$query->bindValue(':layout_id', $this->layoutId);
		}
		$urlParts = $query->queryOne();
		if (isset($urlParts['name'])) {
			$urlParts['name'] = Inflector::slug(strip_tags($urlParts['name']));
		}
		$this->_menuUrlParts = $urlParts;
	}

	protected function getImageHeight() {
		return ceil($this->maxImageWidth * $this->imageEdgeRatio);
	}
}