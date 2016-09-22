<?php

use backend\assets\FormAsset;
use backend\models\CategoryForm;
use backend\utilities\CKEditor;
use backend\utilities\KCFinder;
use pavlinter\display\DisplayImage;
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

	<div id="image-browser"<?= $model->imageFilename ? 'style="display: none;"' : '' ?>>
		<?= /** @noinspection HtmlUnknownTarget */
		$form->field($model, 'imageFilename')->widget(KCFinder::className(), [
			'kcfBrowseOptions' => [
				'type' => 'images'
			],
			'buttonLabel' => Yii::t('back', 'Select image'),
			'thumbTemplate' => '<li>' . Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', '#', [
					'class' => 'removeThumbs'
				]) .'<img src="{thumbSrc}" /><input type="hidden" name="{inputName}" value="{inputValue}"></li>'
		]); ?>
	</div>
	<div id="image-thumbnail"<?= $model->imageFilename ? '' : 'style="display: none;"' ?>>
		<div class="col-sm-2 text-right"><strong><?= Yii::t('back', 'Image for perex'); ?></strong></div>
		<div class="col-sm-9 image-wrapper">
			<?= Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', '#', [
				'class' => 'showBrowser'
			]); ?>
			<?= DisplayImage::widget([
				'width' => 100,
				'height' => 100,
				'image' => $model->imageFilename,
				'category' => 'all'
			]); ?>
		</div>
		<?php if ($model->imageFilename) echo $form->field($model, 'imageFilename')->hiddenInput()->label(false); ?>
	</div>

	<?= $form->field($model, 'description')->widget(CKEditor::className(), [
		'options' => ['rows' => 4],
		'preset' => 'custom',
		'clientOptions' => [
			'height' => 300,
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
