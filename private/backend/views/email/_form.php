<?php

use backend\assets\FormAsset;
use backend\models\EmailForm;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\EmailForm */
/* @var $form yii\bootstrap\ActiveForm */

FormAsset::register($this);
?>

<div class="email-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
        'options' => [
	        'id' => 'layout-form'
        ]
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>


	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		EmailForm::PROPERTY_ACTIVE => Yii::t('back', 'Active')
	], [
		'id' => 'modal-boxes',
		'data-main' => 0,
		'data-public' => 0
	]) ?>

	<div class="form-group modal-footer">
        <?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
