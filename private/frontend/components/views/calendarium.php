<?php
/* @var $this \yii\web\View */
/* @var $items common\models\ContentRecord[] */
/* @var $menuUrlParts array */
/* @var $title string */
/* @var $columnsCount integer */
/* @var $wordsCount integer */
/* @var $withImage boolean */
/* @var $withDate boolean */
/* @var $maxImageSize array */

use frontend\models\ArticleContent;
use pavlinter\display\DisplayImage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

if ($items) {
	echo '<div class="perexes-list">';
	if ($columnsCount > 3) $columnsCount = 3;
	$i = 0;
	foreach ( $items as $item ) {
		$articleUrlParts = [
			'page/article',
			'ida' => $item->id,
			'article' => Inflector::slug(strip_tags($item->title)),
			'web' => \Yii::$app->request->get('web'),
			'language' => \Yii::$app->request->get('language')
		];
		if (!$menuUrlParts) {
			$menuUrlParts = ArticleContent::getMenuUrlParts($item->id);
		}
		$url = ArrayHelper::merge($articleUrlParts, $menuUrlParts);
		$test = $i % $columnsCount;
		if ($test == 0) {
			echo '<div class="row">';
		}
		echo '<div class="col s12' . ($columnsCount == 2 ? ' l6' : ($columnsCount == 3 ? ' l4' : '')) . '">';
		echo '<div class="row">';
		if ($withImage) {
			echo '<div class="col s12 l1">';
			if (isset($item->image)) {
				$image = DisplayImage::widget([
					'width' => $maxImageSize['width'],
					'height' => $maxImageSize['height'],
					'options' => [
						'class' => 'responsive-img hoverable',
						'title' => $item->title
					],
					'category' => 'all',
					'image' => $item->image->filename
				]);
				echo Html::a($image, $url);
			}
			echo '</div>';
			echo '<div class="col s12 l11">';
		} else {
			echo '<div class="col s12">';
		}
		$dateTimeString = '<span class="subheader">( *&nbsp;' . Yii::$app->formatter->asDate($item->content_date, 'dd.MM.y');
		if ($item->content_end_date) {
			$dateTimeString .= ' , &dagger;&nbsp;' . Yii::$app->formatter->asDate($item->content_end_date, 'dd.MM.y');
		}
		$dateTimeString .= ' )</span>';
		echo '<h4>' . $dateTimeString . Html::a($item->title, $url) . '</h4>';
		$text = strip_tags($item->perex, 'a, strong, b, em, i');
		echo '<p>';
		if ($wordsCount) {
			echo StringHelper::truncateWords($text, $wordsCount);
		} else {
			echo $text;
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		if ($test == ($columnsCount - 1)) {
			echo '</div>';
		}
		++$i;
	}
	if ($test != ($columnsCount - 1)) {
		echo '</div>';
	}
	echo '</div>';
}