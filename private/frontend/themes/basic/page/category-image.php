<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $category \common\models\Category */

use frontend\components\CategoryArticlesList;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$category                    = $menuContent->category;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

if ($category->image) {
	echo DisplayImage::widget([
		'options' => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image' => $category->image->filename
	]);
}

if ( $category->description ) {
	echo '<div class="description category">';
	echo FrontEndHelper::parseContent( $category->description );
	echo '</div>';
}

echo CategoryArticlesList::widget( [
	'categoryId'     => $category->id,
	'viewName'       => 'actualities',
	'wordsCount'     => 50,
	'withImage'      => true,
	'imageEdgeRatio' => 0.667,
	'maxImageWidth'  => 400
] );