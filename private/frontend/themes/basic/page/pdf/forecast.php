<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\components\Forecast;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$page = $content->content;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ($page->image) {
	echo DisplayImage::widget([
		'width' => 200,
		'height' => 200,
		'category' => 'all',
		'image' => $page->image->filename
	]);
}

if ($page->perex) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ($page->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parsePdfContent($page->description);
	echo '</div>';
}

echo Forecast::widget(
	['viewName' => 'full2ColumnsForecast']
);

echo '</body>';