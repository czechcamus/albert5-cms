<?php
/* @var $this yii\web\View */
/* @var $model common\models\FileTextRecord */
/* @var $form yii\bootstrap\ActiveForm */

use backend\assets\FormAsset;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

FormAsset::register($this);
?>

<div class="file-texts-form">

	<?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
		'fieldClass' => ActiveField::className(),
		'fieldConfig' => Yii::$app->params['fieldConfig'],
		'options' => [
				'id' => 'file-texts-form'
		]
	]); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'description')->textarea() ?>

	<div class="form-group modal-footer">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
	</div>

	<?php ActiveForm::end(); ?>

</div>