<?php
/* @var $gallery \common\models\Gallery */
/* @var $image string */
/* @var $align string */

use yii\helpers\Html;

if ($gallery) {
	echo '<div class="col s12 m6 gallery-link ' . $align . '">';
	echo '<div class="card">';
	echo '<div class="card-content">';
	echo '<span class="card-title"><i class="material-icons">photo_library</i> ' . Html::a($gallery->title, ['site/gallery', 'id' => $gallery->id, 'c' => 'article']) . '</span>';
	echo Html::a($image, ['site/gallery', 'id' => $gallery->id, 'c' => 'article']);
	echo '</div>';
	echo '<div class="card-action">';
	echo Html::a(Yii::t('front', 'Skip to gallery'), ['site/gallery', 'id' => $gallery->id, 'c' => 'article'], [
		'class' => 'waves-effect waves-light btn'
	]);
	echo '</div>';
	echo '</div>';
	echo '</div>';
}