<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\components\AdditionalField;
use frontend\components\CategoryArticlesList;
use frontend\components\InvitationsList;
use frontend\components\Weather;
use frontend\utilities\FrontEndHelper;

$page = $content->content;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ($page->image) {
	echo \pavlinter\display\DisplayImage::widget([
		'category' => 'all',
		'image' => $page->image->filename
	]);
}

echo '<p class="info-page">';
echo AdditionalField::widget([
	'pageId' => $page->id,
	'addFieldId' => Yii::$app->params[Yii::$app->language]['statusAddFieldId'],
	'viewName' => 'poolFields'
]) . ' <span>&#9724;</span> ';
echo AdditionalField::widget([
	'pageId' => $page->id,
	'addFieldId' => Yii::$app->params[Yii::$app->language]['openTimeAddFieldId'],
	'viewName' => 'poolFields'
]) . '<br />';
echo Weather::widget(
	['viewName' => 'poolWeather']
) . ' ';
echo AdditionalField::widget([
	'pageId' => $page->id,
	'addFieldId' => Yii::$app->params[Yii::$app->language]['watterTempAddFieldId'],
	'viewName' => 'poolFields'
]);
echo '</p>';

if ($page->perex) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ($page->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parsePdfContent($page->description);
	echo '</div>';
}

echo InvitationsList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['poolInvitationsCategoryId'],
	'viewName' => 'invitations',
	'wordsCount' => 30,
	'withImage' => true,
	'maxImageWidth' => 200,
	'columnsCount' => 1,
	'imageEdgeRatio' => 0.5,
	'noMessage' => true
]);

echo CategoryArticlesList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['poolActualitiesCategoryId'],
	'viewName' => 'actualities',
	'wordsCount' => 50,
	'withImage' => true,
	'maxImageWidth' => 200,
	'imageEdgeRatio' => 0.5,
	'noMessage' => false
]);

echo '</body>';