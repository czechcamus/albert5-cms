<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $articleContent \frontend\models\ArticleContent */

use frontend\components\CategoryMenuArticlesList;
use frontend\utilities\FrontEndHelper;

$this->title = $menuContent->title . ' - ' . $articleContent->title;
$this->params['menuContent'] = $menuContent;
$this->params['articleContent'] = $articleContent;
$this->params['siblingsWidget'] = [
	'name' => CategoryMenuArticlesList::className(),
	'widgetOptions' => [
		'categoryId' => $menuContent->category->id,
		'articleId'  => $articleContent->id,
		'itemsCount' => 5,
		'withImage'      => true,
		'imageEdgeRatio' => 1,
		'maxImageWidth'  => 60,
		'viewName' => 'imageTitleList',
		'title' => Yii::t( 'front', 'Recent' ) . ' ' . $menuContent->category->title
	]
];

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'article-content';

if ($articleContent->perex) {
	echo '<div class="perex">' . $articleContent->perex . '</div>';
}

if ($articleContent->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($articleContent->description);
	echo '</div>';
}