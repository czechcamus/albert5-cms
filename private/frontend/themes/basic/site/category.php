<?php
/* @var $this yii\web\View */
/* @var $category Category */

use common\models\Category;
use frontend\components\ArticleList;
use frontend\utilities\FrontEndHelper;

$this->title = $category->title;

$this->params['categoryId'] = $category->id;

if ($category->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($category->description);
	echo '</div>';
}

if ($category->articles) {
	echo ArticleList::widget([
		'items' => $category->articles,
		'articlesColumnsCount' => 1,
		'withImage' => true,
		'maxImageWidth' => 250,
		'parentType' => 'category',
	]);
}