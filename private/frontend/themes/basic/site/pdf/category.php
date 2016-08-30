<?php
/* @var $this yii\web\View */
/* @var $content \common\models\Category */

use frontend\components\ArticleList;
use frontend\utilities\FrontEndHelper;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ($content->description) {
	echo '<div class="description category">';
	echo FrontEndHelper::parsePdfContent($content->description);
	echo '</div>';
}

if ($content->articles) {
	echo ArticleList::widget([
		'items' => $content->articles,
		'columnsCount' => 1,
		'maxImageWidth' => 200
	]);
}

echo '</body>';