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

/**
 * Class CategoryArticlesList displays category articles list
 * @package frontend\components
 */
class CategoryArticlesList extends Widget
{
	/** @var int category id */
	public $categoryId;

	/** @var int number of items */
	public $itemsCount = null;

	/** @var int number of columns */
	public $columnsCount = 1;

	/** @var string $title of list */
	public $title = null;

	/** @var bool $checkDate will we check date of article? */
	public $checkDate = false;

	/** @var string order by expression */
	public $orderBy = 'content.order_time DESC';

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

	public function init() {
		parent::init();
		if ($this->categoryId) {
			$language_id = FrontEndHelper::getLanguageIdFromAcronym();

			if ($this->checkDate === true) {

				$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					WHERE (((((`content`.`content_type`=:content_type))
						AND ((content.content_date>=:actual_date) OR (content.content_end_date>=:actual_date)))
						AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
					ORDER BY " . $this->orderBy . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

				$query = ContentRecord::findBySql($sql, [
					':category_id' => $this->categoryId,
					':content_type' => ContentRecord::TYPE_ARTICLE,
					':language_id' => $language_id,
					':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
				]);

			} else {

				$sql = "( SELECT DISTINCT `content`.*
					FROM `content`
					INNER JOIN `article_category` ON article_category.article_id=content.id
					INNER JOIN `category` ON (category.id=article_category.category_id AND category.id=:category_id)
					WHERE ((((((`content`.`content_type`=:content_type))
						AND (IF(`content`.`content_date` IS NULL, `content`.`content_date` IS NULL, `content`.`content_date`<=:actual_date)))
                        AND (IF(`content`.`content_end_date` IS NULL, `content`.`content_end_date` IS NULL, `content`.`content_end_date`>=:actual_date)))
                        AND (`content`.`active`=TRUE)) " . (Yii::$app->user->isGuest ? " AND (`content`.`public`=TRUE))" : '') . "
						AND (`content`.`language_id`=:language_id))
					ORDER BY " . $this->orderBy  . ($this->itemsCount ? " LIMIT " . $this->itemsCount : "");

				$query = ContentRecord::findBySql($sql, [
					':category_id' => $this->categoryId,
					':content_type' => ContentRecord::TYPE_ARTICLE,
					':language_id' => $language_id,
					':actual_date' => Yii::$app->formatter->asDate('now', 'y-MM-dd')
				]);

			}
			$this->_items = $query->all();
		} else {
			throw new InvalidParamException(\Yii::t('front', 'No required parameter given') . ' - categoryId');
		}
	}

	public function run() {
		$config = [
			'items' => $this->_items,
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

	protected function getImageHeight() {
		return ceil($this->maxImageWidth * $this->imageEdgeRatio);
	}
}