<?php
/* @var $this \yii\web\View */
/* @var $item \common\models\ContentRecord|\frontend\models\ArticleContent|\common\models\Category|MenuContent */
/* @var $contentType integer */
/* @var $titleTemplate string */
/* @var $widget DisplayContent */

use frontend\components\DisplayContent;
use frontend\models\MenuContent;
use yii\helpers\Html;

$widget = $this->context;

echo '<div class="all-content">';
if ($contentType == DisplayContent::CONTENT_MENU) {
	$url = $item->getUrl();
	$innerTitle = Html::a($item->title, $url, [
		'title' => $item->title
	]);
	$title = str_replace('{title}', $innerTitle, $titleTemplate);
	$imageTitle = $item->title;
	if ($item->content_type == MenuContent::CONTENT_LINK) {
		$image = null;
		$imageFilename = null;
		$imageLink = null;
		$perex = null;
		$description = null;
	} elseif ($item->content_type == MenuContent::CONTENT_CATEGORY) {
		$image = $item->category->image;
		$imageFilename = $image ? $image->filename : null;
		$imageLink = $url;
		$perex = null;
		$description = $item->category->description;
	} else {
		$image = $item->content->image;
		$imageFilename = $image ? $image->filename : null;
		$imageLink = $url;
		$perex = $item->content->perex;
		$description = $item->content->description;
	}
	$widget->renderContent($title, $image, $imageTitle, $imageFilename, $imageLink, $perex, $description);
} else {
	//TODO dopracovat podle konkrétní potřeby
}
echo '</div>';