<?php
/* @var $this yii\web\View */
/* @var $content ContentRecord */

use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ( $content->image ) {
	echo \pavlinter\display\DisplayImage::widget([
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