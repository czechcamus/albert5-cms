<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\PollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Polls');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Poll');
?>
<div>

	<?php
	if($session['language_id'] || LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['poll/index']
		]);
		echo '</div>';
	}
	?>

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::button(Yii::t('back', 'Create poll'),
			[
				'value' => Url::to(['poll/create']),
				'title' => Yii::t('back', 'Create poll'),
				'class' => 'showModalButton btn btn-success'
			]
		) ?>
	</p>

	<?php if ($session->hasFlash('info')): ?>
		<div class="alert alert-success">
			<?= $session->getFlash('info'); ?>
		</div>
	<?php endif; ?>

	<?= /** @noinspection PhpUnusedParameterInspection */
	GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			'question',
			[
				'attribute' => 'main',
				'filter' => [
					Yii::t('back', 'No'),
					Yii::t('back', 'Yes')
				],
				'value' => function ($model) {
					return $model->main == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
				}
			],
			[
				'attribute' => 'active',
				'filter' => [
					Yii::t('back', 'No'),
					Yii::t('back', 'Yes')
				],
				'value' => function ($model) {
					return $model->active == 1 ? Yii::t('back', 'yes') : Yii::t('back', 'no');
				}
			],
			'end_date:date',

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update} {delete} {results}',
                'buttons' => [
					'update' => function ($url) {
						return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
							'value' => $url,
							'title' => Yii::t('back', 'Update poll'),
							'class' => 'showModalButton btn btn-link',
							'style' => 'padding: 0'
						]);
					},
					'results' => function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-stats" aria-hidden="true"></span>', [
							'poll/results',
							'id' => $key
						],
						[
							'title' => Yii::t('back', 'Results of poll'),
							'class' => 'btn btn-link',
							'style' => 'padding: 0'
						]);
					}
                ]
			]
		],
	]); ?>

</div>