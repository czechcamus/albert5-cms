<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $model \frontend\models\SearchContent */
/* @var $tag string */

use yii\widgets\ListView;

$this->title = Yii::t('front', 'Search results against tag');

echo '<p>' . Yii::t('front', 'Search results for tag') . ': <strong>"' . $tag . '"</strong></p>';

echo '<div class="perexes-list">' . ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView' => '_searchItem'
]) . '</div>';