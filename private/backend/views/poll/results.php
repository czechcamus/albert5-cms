<?php
/* @var $this yii\web\View */
/* @var $model PollRecord */

use common\models\PollRecord;
use fruppel\googlecharts\GoogleCharts;
use yii\helpers\Html;

$this->title = Yii::t('back', 'Poll results');
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Polls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$rows = [];
foreach ( $model->answers as $pollAnswer ) {
	$rows[] = ['c' => [['v' => $pollAnswer->answer], ['v' => $pollAnswer->voices]]];
}
?>

<div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?= GoogleCharts::widget([
		'id' => 'chart-id',
		'visualization' => 'PieChart',
		'data' => [
			'cols' => [
				[
					'id' => 'answers',
					'label' => Yii::t('back', 'Answers'),
					'type' => 'string'
				],
				[
					'id' => 'voices',
					'label' => Yii::t('back', 'Voices'),
					'type' => 'number'
				]
			],
			'rows' => $rows
		],
		'options' => [
			'title' => $model->question,
			'width' => 600,
			'height' => 400,
		],
		'responsive' => true,
	]) ?>

	<div style="margin-top: 20px">
	<?= Html::a(Yii::t('back', 'Close'), ['poll/index'], [
			'class' => 'btn btn-primary'
	]); ?>
	</div>

</div>
