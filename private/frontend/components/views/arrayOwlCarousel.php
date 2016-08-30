<?php
/* @var $items array */

use sersid\owlcarousel\Asset;
use yii\helpers\Html;

Asset::register($this);

if ($items) {
	echo '<div id="owl-image-links">';
	foreach ( $items as $item ) {
		echo '<div class="item">';
		echo Html::a( Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/icons/' . $item['img'] . '_bg.png', [
			'alt' => $item['title'] . ' - logo',
			'class' => 'responsive-img'
		] ), $item['url'], [
			'style' => 'background-image: url(' . Yii::$app->request->baseUrl . '/basic-assets/img/icons/' . $item['img'] . '_logo.png)',
			'title' => $item['title']
		]);
		echo '</div>';
	}
	echo '</div>';
/*
	echo '<div class="customNavigation">';
    echo '<a class="prev" title="' . Yii::t('front', 'previous') . '"><i class="material-icons medium">navigate_before</i></a>';
    echo '<a class="next" title="' . Yii::t('front', 'next') . '"><i class="material-icons medium">navigate_next</i></a>';
	echo '</div>';
*/
}
