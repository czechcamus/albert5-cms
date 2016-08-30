<?php

use backend\assets\FormAsset;
use backend\models\CategoryForm;
use backend\utilities\CKEditor;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model CategoryForm */
/* @var $form yii\bootstrap\ActiveForm */

FormAsset::register($this);
?>

<div>

	<?php $form = ActiveForm::begin([
		'layout' => 'horizontal',
		'fieldClass' => ActiveField::className(),
		'fieldConfig' => Yii::$app->params['fieldConfig'],
		'options' => [
			'id' => 'category-form'
		]
	]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'description')->widget(CKEditor::className(), [
		'options' => ['rows' => 4],
		'preset' => 'custom',
		'clientOptions' => [
			'height' => 400,
			'toolbarGroups' => [
				['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
				['name' => 'styles'],
				['name' => 'editing', 'groups' => ['tools']],
				'/',
				['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
				['name' => 'paragraph', 'groups' => ['list', 'indent', 'align', 'blocks']],
				['name' => 'links'],
				['name' => 'insert'],
				['name' => 'others'],
			],
			'removeButtons' => 'Templates,CreateDiv,Styles,Font,FontSize,Smiley,Iframe,Flash,PageBreak,Article,Gallery,Sound,Youtube,Poll'
		]
	]) ?>

	<?= /** @noinspection PhpUndefinedMethodInspection */
	$form->field($model, 'boxes')->inline()->checkboxList([
		CategoryForm::PROPERTY_MAIN => Yii::t('back', 'Main'),
		CategoryForm::PROPERTY_PUBLIC => Yii::t('back', 'Public'),
		CategoryForm::PROPERTY_ACTIVE => Yii::t('back', 'Active')
	], [
		'id' => 'modal-boxes'
	]) ?>

    <div class="form-group modal-footer">
	    <?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), [
		    'class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary'
	    ]) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
