<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;

$this->title = Yii::t('back', 'Users');
$this->params['breadcrumbs'][] = $this->title;
$modelClass = Yii::t('back', 'user');
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button(
	        Yii::t('back', 'Create {modelClass}', compact('modelClass')),
            [
	            'value' => Url::to(['user/create']),
	            'title' => Yii::t('back', 'Create {modelClass}', compact('modelClass')),
	            'class' => 'showModalButton btn btn-success'
            ]) ?>
    </p>

	<?php if ($session->hasFlash('info')): ?>
		<div class="alert alert-success">
			<?= $session->getFlash('info'); ?>
		</div>
	<?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
	        [
		        'attribute' => 'status',
		        'value' => function ($model) {
			        return $model->getStatusText();
		        }
	        ],
	        [
		        'label' => Yii::t('back', 'Roles'),
		        'value' => function ($model) {
			        $roles = Yii::$app->authManager->getRolesByUser($model->id);
			        $names = [];
			        foreach ( $roles as $role ) {
				        $names[] = $model->getRoleText($role->name);
			        }

			        return implode(',', $names);
		        }
	        ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
	                'update' => function ($url) {
		                return Html::button('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', [
			                'value' => $url,
			                'title' => Yii::t('back', 'Update user'),
			                'class' => 'showModalButton btn btn-link',
			                'style' => 'padding: 0'
		                ]);
	                },
	                'delete' => function ($url) {
		                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
			                'title' => Yii::t('back', 'Delete user'),
			                'data-confirm' => Yii::t('back', 'Are you really sure you want to delete this user?'),
			                'data-method' => 'post',
			                'data-pjax' => '0'
		                ]);
	                }
                ]
            ],
        ],
    ]); ?>

</div>
