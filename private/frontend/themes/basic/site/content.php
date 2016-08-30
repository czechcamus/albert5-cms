<?php
/* @var $this yii\web\View */
/* @var $content ContentRecord */

use common\models\ContentRecord;
use frontend\utilities\FrontEndHelper;

$this->title = $content->title;

$this->params['contentId'] = $content->id;

if ($content->perex) {
	echo '<div class="perex">' . $content->perex . '</div>';
}

if ($content->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($content->description);
	echo '</div>';
}