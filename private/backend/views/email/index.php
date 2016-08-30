<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\EmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Email');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="email-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->can('manager')): ?>
        <p>
            <?= Html::button(
	            Yii::t('back', 'Create email'),
	            [
		            'value' => Url::to(['email/create']),
		            'title' => Yii::t('back', 'Create email'),
		            'class' => 'showModalButton btn btn-success'
	            ]
            ) ?>
        </p>
    <?php endif; ?>

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

            'email',
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

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
	            'buttons' => [
		            'update' => function ($url) {
			            return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
				            'value' => $url,
				            'title' => Yii::t('back', 'Update'),
				            'class' => 'showModalButton btn btn-link',
				            'style' => 'padding: 0'
			            ]);
		            },
	                'delete' => function ($url, $model) {
		                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
			                'title' => Yii::t('back', 'Delete'),
			                'data-confirm' => Yii::t('back', 'Are you sure you want to delete this email?'),
			                'data-method' => 'post',
			                'data-pjax' => '0'
		                ]);
	                }
	            ]
            ]
        ]
    ]); ?>

</div>
