<?php
/* @var $items array */
/* @var  $contentId integer|null */

use frontend\utilities\FrontEndHelper;
use yii\helpers\Html;

if ($items) {
	echo '<div class="row side-menu-items">';
	echo '<div class="col s12">';
	echo '<h3><span>' . ($contentId ? Yii::t('front', 'Used tags') : Yii::t('front', 'Most used tags')) . '</span></h3>';
	$bullet = false;
	foreach ( $items as $item ) {
		if ($bullet) {
			echo ' <span class="tags-delimiter">&bigstar;</span> ';
		} else {
			$bullet = true;
		}
		echo '<span style="' . FrontEndHelper::getTagItemFontSize($item['frequency']) . '">' . Html::a($item['name'], [
				'page/tag',
				'tag' => $item['name'],
				'web' => \Yii::$app->request->get('web'),
				'language' => \Yii::$app->request->get('language')
			]) . '</span> ';
	}
	echo '</div>';
	echo '</div>';
}