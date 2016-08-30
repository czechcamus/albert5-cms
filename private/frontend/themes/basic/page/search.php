<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $model \frontend\models\SearchContent */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('front', 'Search results');

echo '<p>' . Yii::t('front', 'Search results for expression') . ': <strong>"' . Html::encode($model->q) . '"</strong></p>';

echo '<div class="perexes-list">' . ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView' => '_searchItem'
]) . '</div>';
