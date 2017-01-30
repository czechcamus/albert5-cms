<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $category \common\models\Category */

use frontend\components\InvitationsArchiveListMenu;
use frontend\utilities\FrontEndHelper;

$this->title             = $menuContent->title;
$this->params['content'] = $menuContent;
$category                = $menuContent->category;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

if ( $category->description ) {
	echo '<div class="description category">';
	echo FrontEndHelper::parseContent( $category->description );
	echo '</div>';
}

echo InvitationsArchiveListMenu::widget( [
	'categoryId'     => $category->id,
	'wordsCount'     => 50,
	'withImage'      => true,
	'maxImageWidth'  => 200,
	'columnsCount'   => 1,
	'imageEdgeRatio' => 0.667,
	'viewName'       => 'invitations'
] );
