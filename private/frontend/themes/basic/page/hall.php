<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\components\CategoryArticlesList;
use frontend\components\InvitationsList;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page                        = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

if ( $page->image ) {
	echo DisplayImage::widget( [
		'options'  => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image'    => $page->image->filename
	] );
}

if ( $page->perex ) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ( $page->description ) {
	echo '<div class="description" style="margin-bottom: 2rem">';
	echo FrontEndHelper::parseContent( $page->description );
	echo '</div>';
}

echo InvitationsList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['hallInvitationsCategoryId'],
	'viewName' => 'invitations',
	'wordsCount' => 30,
	'withImage' => true,
	'maxImageWidth' => 200,
	'columnsCount' => 1,
	'imageEdgeRatio' => 0.5,
	'noMessage' => true
]);

echo CategoryArticlesList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['hallActualitiesCategoryId'],
	'viewName' => 'actualities',
	'wordsCount' => 50,
	'withImage' => true,
	'maxImageWidth' => 200,
	'imageEdgeRatio' => 0.5,
	'noMessage' => true
]);