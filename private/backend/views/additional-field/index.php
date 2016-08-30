<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Additional fields');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'Additional field');
?>
<div>

	<?php
	if($session['language_id'] || LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['additional-field/index']
		]);
		echo '</div>';
	}
	?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(
	        Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	        [
		        'value' => Url::to(['additional-field/create']),
		        'title' => Yii::t('back', 'Create {modelClass}', compact('modelClass')),
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
	    'id' => 'additional-field-list',
	    'dataProvider' => $dataProvider,
	    'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'label',
            [
	            'class' => 'yii\grid\ActionColumn',
	            'template' => '{update} {delete}',
	            'buttons' => [
		            'update' => function ($url) {
			            return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
				            'value' => $url,
				            'title' => Yii::t('back', 'Update additional field'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]);
		            },
		            'delete' => function ($url, $model) {
			            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
				            'title' => Yii::t('back', 'Delete additional field'),
				            'data-confirm' => Yii::t('back', 'Are you sure you want to delete this additional field?'),
				            'data-method' => 'post',
				            'data-pjax' => '0',
			            ]);
		            }
	            ]
            ],
        ],
    ]); ?>

</div>