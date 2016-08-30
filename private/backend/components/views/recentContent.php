<?php
/* @var $items common\models\Page[]|common\models\Article[] */
/* @var $controllerId string */
/* @var $translatedWordItems string */
/* @var $translatedWordItems2 string */
/* @var $contentType int */

use common\models\ContentRecord;
use yii\helpers\Html;

echo '<h2>' . Yii::t('back', 'Recent {items}', [
		'items' => $translatedWordItems
	]) . '</h2>';

if ($items) {

	foreach ( $items as $item ) {
		echo '<div class="row"><div class="col-xs-12">';
		echo '<div class="pull-right">' . ($item->public ? '<span class="glyphicon glyphicon-eye-open" title="' . Yii::t('back', 'Public') . '"></span>' : '<span class="glyphicon glyphicon-eye-close" title="' . Yii::t('back', 'Not public') . '"></span>') . ' '
		     . ($item->active ? '<span class="glyphicon glyphicon-ok-circle" title="' . Yii::t('back', 'Active') . '"></span>' : '<span class="glyphicon glyphicon-remove-circle" title="' . Yii::t('back', 'Not active') . '"></span>') . '</div>';
		echo '<h3 style="margin-top: 0">' . Html::a($item->title, [$controllerId . '/update', 'id' => $item->id]) . '<br><small>' . Yii::t('back', 'Last update') . ': '
		     . Yii::$app->formatter->format($item->updated_at, ['datetime', 'php:d.m.Y, H:i:s']) . '</small></h3>';
		echo $item->perex;
		echo '</div></div>';
	}

	echo '<p class="notice">' . Yii::t('back', 'Number {items2} in CMS', [
					'items2' => $translatedWordItems2
			]) . ': ' . ContentRecord::getRecordsCount($contentType) . '</p>';

} else {

	echo '<p class="text-warning">' . Yii::t('back', 'No {items} in CMS', [
			'items' => $translatedWordItems
		]) . '</p>';

}

echo Html::a(Yii::t('back', 'Manage {items}', [
	'items' => $translatedWordItems
]), [$controllerId . '/index'], ['class' => 'btn btn-primary']);