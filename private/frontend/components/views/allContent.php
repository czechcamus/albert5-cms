<?php
/* @var $this \yii\web\View */
/* @var $item \common\models\ContentRecord|\frontend\models\ArticleContent|\common\models\Category|MenuContent */
/* @var $wordsCountPerex integer */
/* @var $wordsCountDescription integer */
/* @var $contentType integer */
/* @var $contentOptions array */
/* @var $titleTemplate string */
/* @var $widget DisplayContent */

use frontend\components\DisplayContent;
use frontend\models\MenuContent;
use yii\helpers\Html;
use yii\helpers\StringHelper;

$widget = $this->context;

echo '<div class="all-content">';
if ($contentType == DisplayContent::CONTENT_MENU) {
	$title = null;
	$imageTitle = null;
	$image = null;
	$imageFilename = null;
	$imageLink = null;
	$perex = null;
	$description = null;
	$url = $item->getUrl();
	if ($item->content_type == MenuContent::CONTENT_LINK) {
		$innerTitle = Html::a($item->title, $url, [
			'title' => $item->title
		]);
		$title = str_replace('{title}', $innerTitle, $titleTemplate);
	} elseif ($item->content_type == MenuContent::CONTENT_CATEGORY) {
		if ($contentOptions['title']) {
			$innerTitle = Html::a($item->category->title, $url, [
				'title' => $item->category->title
			]);
			$title = str_replace('{title}', $innerTitle, $titleTemplate);
		}
		if ($contentOptions['image']) {
			$imageTitle    = $item->category->title;
			$image         = $item->category->image;
			$imageFilename = $image ? $image->filename : null;
			$imageLink     = $url;
		}
		if ($contentOptions['description']) {
			if ($wordsCountDescription) {
				$text = strip_tags($item->category->description, '<a>, <strong>, <b>, <em>, <i>');
				$description = StringHelper::truncateWords($text, $wordsCountDescription);
			} else {
				$description = $item->category->description;
			}
		}
	} else {
		if ($contentOptions['title']) {
			$innerTitle = Html::a($item->content->title, $url, [
				'title' => $item->content->title
			]);
			$title = str_replace('{title}', $innerTitle, $titleTemplate);
		}
		if ($contentOptions['image']) {
			$imageTitle    = $item->content->title;
			$image         = $item->content->image;
			$imageFilename = $image ? $image->filename : null;
			$imageLink     = $url;
		}
		if ($contentOptions['perex']) {
			if ($wordsCountPerex) {
				$text = strip_tags($item->content->perex, '<a>, <strong>, <b>, <em>, <i>');
				$perex = StringHelper::truncateWords($text, $wordsCountPerex);
			} else {
				$perex = $item->content->perex;
			}
		}
		if ($contentOptions['description']) {
			$description = $item->content->description;
		}
	}
	$widget->renderContent($title, $image, $imageTitle, $imageFilename, $imageLink, $perex, $description);
} else {
	//TODO dopracovat podle konkrétní potřeby
}
echo '</div>';