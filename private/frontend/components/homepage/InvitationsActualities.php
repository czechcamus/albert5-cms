<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 19.8.2016
 * Time: 19:54
 */

namespace frontend\components\homepage;


use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;
use Yii;
use yii\base\Widget;
use yii\helpers\Inflector;

/**
 * Class InvitationsActualities displays invitations and actualities
 * @package frontend\components\homepage
 */
class InvitationsActualities extends Widget {

	/** @var  int invitations category id */
	public $invitationsCategoryId;

	/** @var  int actualities category id */
	public $actualitiesCategoryId;

	/** @var string name of view file */
	public $viewName = 'invitationsActualitiesHp';

	/** @var int number of items */
	public $itemsCount = null;

	/** @var int number of columns */
	public $columnsCount = 1;

	/** @var int perex words count  */
	public $wordsCount = null;

	/** @var int max image width */
	public $maxImageWidth = 200;

	/** @var int image edge ratio */
	public $imageEdgeRatio = 1;

	protected $_invitationsItems;

	protected $_actualitiesItems;

	protected $_invitationsMenuUrlParts = [];

	protected $_actualitiesMenuUrlParts = [];

	public function init() {
		parent::init();
		if (!$this->invitationsCategoryId)
			$this->invitationsCategoryId = Yii::$app->params[Yii::$app->language]['defaultInvitationsCategoryId'];
		if (!$this->actualitiesCategoryId)
			$this->actualitiesCategoryId = Yii::$app->params[Yii::$app->language]['defaultActualitiesCategoryId'];

		$language_id = FrontEndHelper::getLanguageIdFromAcronym();
		$web_id = FrontEndHelper::getWebIdFromTextId(\Yii::$app->request->get('web', \Yii::$app->params['defaultWeb']));

		// Invitations
		$sql = "( SELECT DISTINCT `content`.*
				FROM `content`
				INNER JOIN `article_category` ON article_category.article_id=content.id
				INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
				INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
				INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
				INNER JOIN `menu` ON menu.id=menu_item.menu_id
				WHERE ((((((`content`.`content_type`=:content_type))
					AND ((content.content_date>=:actual_date) OR (content.content_end_date>=:actual_date)))
					AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
					AND (`content`.`language_id`=:language_id))
					AND (`menu`.`web_id`=:web_id))
				ORDER BY content.content_date, content.content_time ASC" . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

		$this->_invitationsItems = ContentRecord::findBySql($sql, [
			':category_id' => $this->invitationsCategoryId,
			':content_type' => ContentRecord::TYPE_ARTICLE,
			':language_id' => $language_id,
			':web_id' => $web_id,
			':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
		])->all();

		$this->setMenuUrlParts('invitations');

		$invitationsCount = count($this->_invitationsItems);

		if ($invitationsCount < $this->itemsCount) {

			$limit = $this->itemsCount - $invitationsCount;

			$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					INNER JOIN `menu_item_content` ON menu_item_content.category_id=category.id
					INNER JOIN `menu_item` ON menu_item.id=menu_item_content.menu_item_id
					INNER JOIN `menu` ON menu.id=menu_item.menu_id
					WHERE (((((((`content`.`content_type`=:content_type))
						AND (IF(`content`.`content_date` IS NULL, `content`.`content_date` IS NULL, `content`.`content_date`<=:actual_date)))
                        AND (IF(`content`.`content_end_date` IS NULL, `content`.`content_end_date` IS NULL, `content`.`content_end_date`>=:actual_date)))
                        AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
						AND (`menu`.`web_id`=:web_id))
					ORDER BY content.updated_at DESC" . ($this->itemsCount ? " LIMIT " . $limit : "");

			$this->_actualitiesItems = ContentRecord::findBySql($sql, [
				':category_id' => $this->actualitiesCategoryId,
				':content_type' => ContentRecord::TYPE_ARTICLE,
				':language_id' => $language_id,
				':web_id' => $web_id,
				':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
			])->all();

			$this->setMenuUrlParts('actualities');

		}
	}

	public function run() {
		$config = [
			'invitationsItems' => $this->_invitationsItems,
			'invitationsMenuUrlParts' => $this->_invitationsMenuUrlParts,
			'actualitiesItems' => $this->_actualitiesItems,
			'actualitiesMenuUrlParts' => $this->_actualitiesMenuUrlParts,
			'itemsCount' => $this->itemsCount,
			'columnsCount' => $this->columnsCount,
			'wordsCount' => $this->wordsCount,
			'maxImageSize' => [
				'width' => $this->maxImageWidth,
				'height' => $this->getImageHeight()
			]
		];
		return $this->render($this->viewName, $config);
	}

	/**
	 * Sets url parts array
	 * @param $itemsType string invitations or actualities
	 */
	public function setMenuUrlParts( $itemsType ) {
		$sql = "SELECT menu_item.id,menu_item.title AS name FROM category, menu_item_content, menu_item WHERE category.id=:id AND category.id=menu_item_content.category_id AND menu_item_content.menu_item_id=menu_item.id";
		$query = Yii::$app->db->createCommand($sql);
		$query->bindValue(':id', $this->{$itemsType . 'CategoryId'});
		$urlParts = $query->queryOne();
		if (isset($urlParts['name'])) {
			$urlParts['name'] = Inflector::slug(strip_tags($urlParts['name']));
		}
		$this->{'_' . $itemsType . 'MenuUrlParts'};
	}

	protected function getImageHeight() {
		return ceil($this->maxImageWidth * $this->imageEdgeRatio);
	}

}