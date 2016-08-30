<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $articleContent \frontend\models\ArticleContent */

use frontend\utilities\FrontEndHelper;

$this->title = $menuContent->title . ' - ' . $articleContent->title;
$this->params['menuContent'] = $menuContent;
$this->params['articleContent'] = $articleContent;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'article-content';

if ($articleContent->perex) {
	echo '<div class="perex">' . $articleContent->perex . '</div>';
}

if ($articleContent->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($articleContent->description);
	echo '</div>';
}