<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\components\Forecast;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$this->title = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

if ($page->image) {
	echo DisplayImage::widget([
		'options' => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image' => $page->image->filename
	]);
}

if ($page->perex) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ($page->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($page->description);
	echo '</div>';
}

echo Forecast::widget(
	['viewName' => 'full2ColumnsForecast']
);