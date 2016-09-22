<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.9.2015
 * Time: 14:06
 */

namespace frontend\components;


use common\models\Category;
use common\models\ContentRecord;
use frontend\models\ArticleContent;
use frontend\models\MenuContent;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\bootstrap\Html;

/**
 * Class DisplayContent displays content of requested type in given view file
 * @property $contentId integer is Id of content
 * @property $contentType integer is code of requested content type
 * @property $titleTemplate string template for displaying title of content
 * @property $viewName string is name of view file
 * @package frontend\components
 */
class DisplayContent extends Widget
{
	/** @var integer $contentId */
	public $contentId;

	/** @var integer type of content */
	public $contentType;

	/** @var string type template for title */
	public $titleTemplate = '<h3>{title}</h3>';

	/** @var string $viewName */
	public $viewName = 'allContent';

	const CONTENT_PAGE = 1;
	const CONTENT_ARTICLE = 2;
	const CONTENT_CATEGORY = 3;
	const CONTENT_MENU = 4;

	private $_item;

	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	public function init() {
		parent::init();
		if ($this->contentId) {
			if (!$this->contentType)
				$this->contentType = self::CONTENT_PAGE;
			$conditions = [
				'id' => $this->contentId,
				'active' => 1
			];
			if (\Yii::$app->user->isGuest)
				$conditions['public'] = 1;
			switch ($this->contentType) {
				case self::CONTENT_ARTICLE:
					$this->_item = ArticleContent::findOne($conditions);
					break;
				case self::CONTENT_CATEGORY:
					$this->_item = Category::findOne($conditions);
					break;
				case self::CONTENT_MENU:
					$this->_item = MenuContent::findOne($conditions);
					break;
				default:
					$this->_item = ContentRecord::findOne($conditions);
					break;
			}
		} else {
			throw new InvalidConfigException( \Yii::t('front', 'No required parameter given') . ' - contentId' );
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run() {
		return $this->render($this->viewName, [
			'item' => $this->_item,
			'contentType' => $this->contentType,
			'titleTemplate' => $this->titleTemplate
		]);
	}

	/**
	 * Renders given content
	 *
	 * @param $title
	 * @param $image
	 * @param $imageTitle
	 * @param $imageFilename
	 * @param $imageLink
	 * @param $perex
	 * @param $description
	 */
	public function renderContent( $title, $image, $imageTitle, $imageFilename, $imageLink, $perex, $description ) {
		ob_start();
		echo $title;
		if ($image) {
			if ($imageLink) {
				echo Html::a(DisplayImage::widget([
					'options' => [
						'class' => 'responsive-img hoverable',
						'title' => $imageTitle
					],
					'category' => 'all',
					'image' => $imageFilename
				]), $imageLink);
			} else {
				echo DisplayImage::widget([
					'options' => [
						'class' => 'responsive-img',
						'title' => $imageTitle
					],
					'category' => 'all',
					'image' => $imageFilename
				]);
			}
		}
		if ($perex) {
			echo '<div class="content-perex">' . $perex . '</div>';
		}
		if ($description) {
			echo '<div class="content-description">';
			echo FrontEndHelper::parseContent( $description );
			echo '</div>';
		}
		ob_flush();
		ob_end_clean();
	}
}