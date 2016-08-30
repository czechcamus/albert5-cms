<?php
/* @var $this \yii\web\View */
/* @var $item \common\models\ContentRecord */
/* @var $url array */
/* @var $maxImageSize array */
/* @var $wordsCount int */
/* @var $columnsCount int */
/* @var $itemType string */

use pavlinter\display\DisplayImage;
use yii\helpers\Html;
use yii\helpers\StringHelper;

echo '<div class="col s12' . ( $columnsCount == 2 ? ' m6' : ( $columnsCount == 3 ? ' m6 l4' : '' ) ) . '">';
echo '<div class="row">';
if (isset($item->image)) {
	echo '<div class="col s12 m6">';
	$image = DisplayImage::widget( [
		'width'    => $maxImageSize['width'],
		'height'   => $maxImageSize['height'],
		'options'  => [
			'class' => 'responsive-img hoverable',
			'title' => $item->title
		],
		'category' => 'all',
		'image'    => $item->image->filename
	] );
	echo Html::a( $image, $url );
	echo '</div>';
	echo '<div class="col s12 m6">';
} else {
	echo '<div class="col s12">';
}
echo '<h5 class="col s12 truncate" title="' . $item->title . '" style="font-size: 150%; margin: 0 0 0.2rem; padding: 0;">' . Html::a( $item->title, $url ) . '</h5>';
$text = strip_tags( $item->perex, 'a, strong, b, em, i' );
echo '<p style="margin: 0.5rem 0 0.2rem;">';
if ( $wordsCount ) {
	echo StringHelper::truncateWords( $text, $wordsCount );
} else {
	echo $text;
}
echo '</p>';
if ($itemType == 'invitation') {
	$dateTimeString = Yii::$app->formatter->asDatetime($item->content_date, 'php: d.m.Y') . ($item->content_time ? ', ' . Yii::$app->formatter->asTime($item->content_time, 'HH:mm') : '') . ($item->content_end_date ? ' - ' . Yii::$app->formatter->asDate($item->content_end_date, 'dd.MM.yy') : '');
} else {
	$dateTimeString = Yii::$app->formatter->asDatetime($item->updated_at, 'php: d.m.Y H:i');
}
echo '<span class="secondary-color-text" style="font-size: 80%;"><i class="material-icons tiny" style="vertical-align: middle;">' . ($itemType == 'invitation' ? 'event' : 'schedule') . '</i> ' . $dateTimeString . '</span>';
echo '</div>';
echo '</div>';
echo '</div>';
