<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$this->title = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

echo $this->renderFile( '@frontend/themes/basic/components/_content.php', [
	'menuContent' => $menuContent,
	'page' => $page
] );