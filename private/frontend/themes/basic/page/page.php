<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

$this->title = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

echo $this->renderFile('@frontend/themes/basic/components/_pageContent.php', compact('menuContent', 'page'));
