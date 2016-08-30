<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page                        = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'stadium-content';

echo $this->renderFile( '@frontend/themes/basic/components/_content.php', [
	'page' => $page,
	'menuContent' => $menuContent
] );