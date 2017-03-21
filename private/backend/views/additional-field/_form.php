<?php
/* @var $this yii\web\View */
/* @var $model common\models\AdditionalFieldRecord */
/* @var $form yii\bootstrap\ActiveForm */

use backend\assets\FormAsset;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

FormAsset::register($this);
?>

<div class="additional-field-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
        'options' => [
	        'id' => 'additional-field-form'
        ]
    ]); ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => 255]) ?>
	
	<div class="form-group modal-footer">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>