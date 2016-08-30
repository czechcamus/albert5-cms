<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $category \common\models\Category */

use frontend\components\CategoryArticlesList;
use frontend\utilities\FrontEndHelper;

$category = $content->category;

echo '<body>';

echo '<h1>' . $content->title . '</h1>';

if ($category->description) {
	echo '<div class="description category">';
	echo FrontEndHelper::parsePdfContent($category->description);
	echo '</div>';
}

if ($category->articles) {
	echo CategoryArticlesList::widget([
		'categoryId' => $category->id,
		'viewName' => 'actualities',
		'itemsCount' => 20,
		'wordsCount' => 50,
		'withImage' => true,
		'maxImageWidth' => 200
	]);
}

echo '</body>';