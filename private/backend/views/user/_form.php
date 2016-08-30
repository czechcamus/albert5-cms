<?php

use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
	    'options' => [
		    'id' => 'user-form'
	    ]
    ]); ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'role')->dropDownList($model->getRoleList(), ['style' => 'width: 10em']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusList(), ['style' => 'width: 10em']) ?>

    <div class="form-group modal-footer">
        <?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
