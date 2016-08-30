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

if ($articleContent->image) {
	echo \pavlinter\display\DisplayImage::widget([
		'width' => 200,
		'height' => 200,
		'options' => [
			'class' => 'image',
			'title' => $articleContent->image->title
		],
		'category' => 'all',
		'image' => $articleContent->image->filename
	]);
}

if ($articleContent->perex) {
	echo '<div class="perex">' . $articleContent->perex . '</div>';
}

if ($articleContent->description) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent($articleContent->description);
	echo '</div>';
}