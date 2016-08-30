<?php
/* @var $gallery \common\models\Gallery */
/* @var $images array */

use yii\helpers\Html;

if ($gallery) {
	$url = ['site/gallery', 'id' => $gallery->id];
	echo '<div class="col s12 gallery-link">';
	echo '<div class="card">';
	echo '<div class="card-content">';
	echo '<span class="card-title"><i class="material-icons">photo_library</i> ' . Html::a($gallery->title, $url) . '</span>';
	if ($images) {
		echo '<div class="row">';
		foreach ( $images as $image ) {
			echo '<div class="col s6 m4 l3">';
			echo $image;
			echo '</div>';
		}
		echo '</div>';
	}
	echo '<span class="btn-link">' . Html::a(Yii::t('front', 'Skip to gallery'), $url, [
			'style' => 'text-transform: uppercase'
		]) . ' <i class="material-icons">navigate_next</i></span>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}