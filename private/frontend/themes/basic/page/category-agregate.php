<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $category \common\models\Category */

use frontend\components\CategoryMenuArticlesList;
use frontend\utilities\FrontEndHelper;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$category                    = $menuContent->category;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

if ( $category->description ) {
	echo '<div class="description category">';
	echo FrontEndHelper::parseContent( $category->description );
	echo '</div>';
}

echo CategoryMenuArticlesList::widget( [
	'categoryId'     => $category->id,
	'viewName'       => 'actualities',
	'itemsCount'     => 20,
	'wordsCount'     => 50,
	'withImage'      => true,
	'imageEdgeRatio' => 0.667,
	'maxImageWidth'  => 200
] );