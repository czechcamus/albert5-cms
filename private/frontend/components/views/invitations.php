<?php
/* @var $items common\models\ContentRecord[] */
/* @var $menuUrlParts array */
/* @var $title string */
/* @var $columnsCount integer */
/* @var $wordsCount integer */
/* @var $withImage boolean */
/* @var $withDate boolean */
/* @var $maxImageSize array */
/* @var $noMessage boolean */


use frontend\models\ArticleContent;
use pavlinter\display\DisplayImage;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

if ($items) {
	echo '<div class="invitations">';
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
		$dateTimeString = Yii::$app->formatter->asDate($item->content_date, 'dd.MM.y');
		if ($item->content_end_date) {
			$dateTimeString .= ' - ' . Yii::$app->formatter->asDate($item->content_end_date, 'dd.MM.y');
		} elseif ($item->content_time) {
			$dateTimeString .= ', ' . Yii::$app->formatter->asTime($item->content_time, 'HH:mm');
		}
		echo '<div class="row">';
		echo '<p class="col s12 datetime"><i class="material-icons left">access_time</i>' . $dateTimeString . '</p>';
		echo '<h4 class="col s12"><i class="material-icons small right">event</i>' . Html::a($item->title, $url) . '</h4>';
		if ($withImage && isset($item->image)) {
			echo '<div class="col s12 l3">';
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
			echo '</div>';
			echo '<div class="col s12 l9">';
		} else {
			echo '<div class="col s12">';
		}
		$text = strip_tags($item->perex, 'a, strong, b, em, i');
		echo '<p>';
		if ($wordsCount) {
			echo StringHelper::truncateWords($text, $wordsCount);
		} else {
			echo $text;
		}
		echo '</p>';
		echo Html::a('<i class="material-icons right">forward</i>' . Yii::t('front', 'details'), $url,
			['class' => 'waves-effect waves-light btn']
		);
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
} else {
	if ($noMessage == false)
		echo '<p>' . Yii::t('front', 'We have no invitations at this time.') . '</p>';
}