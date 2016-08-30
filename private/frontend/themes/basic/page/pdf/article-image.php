<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\ArticleContent */

use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ($content->image) {
	echo DisplayImage::widget([
		'width' => 200,
		'height' => 200,
		'category' => 'all',
		'image' => $content->image->filename
	]);
}

if ($content->perex) {
	echo '<div class="perex">' . $content->perex . '</div>';
}

if ($content->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parsePdfContent($content->description);
	echo '</div>';
}

echo '</body>';