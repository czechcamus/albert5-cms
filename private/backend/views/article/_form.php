<?php
/* @var $this yii\web\View */
/* @var $model backend\models\ArticleForm */
/* @var $form yii\bootstrap\ActiveForm */

use backend\models\ArticleForm;
use backend\utilities\CKEditor;
use backend\utilities\KCFinder;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\widgets\TimePicker;
use pavlinter\display\DisplayImage;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
	'fieldClass' => ActiveField::className()
]); ?>

<div class="row">

    <div class="col-sm-12 col-md-9">

		<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

		<div id="image-browser"<?= $model->imageFilename ? ' style="display: none;"' : '' ?>>
		<?= /** @noinspection HtmlUnknownTarget */
		$form->field($model, 'imageFilename')->widget(KCFinder::className(), [
			'kcfBrowseOptions' => [
				'type' => 'images',
				'lang' => 'cs'
			],
			'buttonLabel' => Yii::t('back', 'Select image'),
			'thumbTemplate' => '<li>' . Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', '#', [
					'class' => 'removeThumbs'
				]) .'<img src="{thumbSrc}" /><input type="hidden" name="{inputName}" value="{inputValue}"></li>'
		]); ?>
		</div>
		<div id="image-thumbnail"<?= $model->imageFilename ? '' : ' style="display: none;"' ?>>
			<strong><?= Yii::t('back', 'Image for perex'); ?></strong>
			<p class="image-wrapper">
				<?= Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', '#', [
					'class' => 'showBrowser'
				]); ?>
				<?= DisplayImage::widget([
					'width' => 100,
					'height' => 100,
					'image' => $model->imageFilename,
					'category' => 'all'
				]); ?>
			</p>
			<?php if ($model->imageFilename) echo $form->field($model, 'imageFilename')->hiddenInput()->label(false); ?>
		</div>

		<?= $form->field($model, 'perex')->widget(CKEditor::className(), [
			'options' => ['rows' => 4],
			'preset' => 'custom',
			'clientOptions' => [
				'height' => 200,
				'toolbarGroups' => [
					['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
					['name' => 'editing', 'groups' => ['tools']],
					['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
					'/',
					['name' => 'paragraph', 'groups' => ['list', 'indent', 'align']],
					['name' => 'links'],
				],
				'removeButtons' => 'Templates,Smiley,Iframe,ShowBlocks,Flash,PageBreak,Anchor,Gallery,Sound,Youtube,Poll,Article'
			]
		]) ?>

		<?= $form->field($model, 'description')->widget(CKEditor::className(), [
			'options' => [
				'rows' => 6
			],
			'preset' => 'custom',
			'clientOptions' => [
				'height' => 400,
				'toolbarGroups' => [
					['name' => 'clipboard', 'groups' => ['mode', 'undo', 'selection', 'clipboard', 'doctools']],
					['name' => 'styles'],
					['name' => 'editing', 'groups' => ['tools']],
					['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
					'/',
					['name' => 'paragraph', 'groups' => ['list', 'indent', 'align', 'blocks']],
					['name' => 'links'],
					['name' => 'insert'],
					['name' => 'others'],
				],
				'removeButtons' => 'Templates,CreateDiv,Styles,Font,FontSize,Smiley,Iframe,Flash,PageBreak,Article'
			]
		]) ?>

		<?= $form->field($model, 'tagValues')->textInput(['maxlength' => 255])->hint(Yii::t('back','Please separate tags by commas!')); ?>

	</div>

	<div class="col-sm-12 col-md-3">

		<?= $form->field($model, 'content_date')->widget(DatePicker::className(), [
			'pluginOptions' => [
				'autoclose' => true
			],
			'options' => [
				'class' => 'form-control',
				'placeholder' => Yii::t('back', 'Date')
			]
		]) ?>

		<?= $form->field($model, 'content_time')->widget(TimePicker::className(), [
			'pluginOptions' => [
				'defaultTime' => false,
				'showSeconds' => false,
				'showMeridian' => false
			],
			'options' => [
				'class' => 'form-control'
			]
		]) ?>

		<?= $form->field($model, 'content_end_date')->widget(DatePicker::className(), [
			'pluginOptions' => [
				'autoclose' => true
			],
			'options' => [
				'class' => 'form-control',
				'placeholder' => Yii::t('back', 'End date')
			]
		]) ?>

		<?= $form->field($model, 'content_end_time')->widget(TimePicker::className(), [
			'pluginOptions' => [
				'defaultTime' => false,
				'showSeconds' => false,
				'showMeridian' => false
			],
			'options' => [
				'class' => 'form-control'
			]
		]) ?>

        <?php
            echo '<div class="form-group field-articleform-categoryboxes">';
            echo Html::label(Yii::t('back', 'Categories'), 'articleform-categoryboxes', ['class' => 'control-label']);
            echo Html::hiddenInput('ArticleForm[categoryBoxes]');
            echo '<div id="articleform-categoryboxes">';
            $model->renderCategoryInputs();
            echo '</div>';
            echo '</div>';
        ?>

		<?= /** @noinspection PhpUndefinedMethodInspection */
		$form->field($model, 'boxes')->inline()->checkboxList([
			ArticleForm::PROPERTY_PUBLIC => Yii::t('back', 'Public'),
			ArticleForm::PROPERTY_ACTIVE => Yii::t('back', 'Active')
		], [
			'id' => 'modal-boxes',
			'data-main' => 0
		]) ?>

        <div class="form-group">
			<?php
			$urlShowAdditionalFieldForm = Url::to(['article/show-additional-field-form', 'id' => $model->item_id]);
			$urlShowAdditionalField = Url::to(['article/show-additional-field']);
			$urlRemoveAdditionalField = Url::to(['article/remove-additional-field']);
			$availableFields= $model->getAvailableFields();
			$availableFieldsAsString = implode(",", $availableFields);
			$this->registerJs(
<<< EOT_JS
				
var availableFields = [{$availableFieldsAsString}];

$(document).on('click', '#add-field-btn', function(e) {

	$('#add-field-btn').hide();
	$('#additional-field-form').show();
	
	$.get('{$urlShowAdditionalFieldForm}', { 'available_fields' : availableFields }, function(data) {
		$('#additional-field-form').html(data);
	});
	e.preventDefault();
	
});

$(document).on('click', '#cancel-field-btn', function(e) {

	$('#add-field-btn').show();
	$('#additional-field-form').hide();
	
	e.preventDefault();
	
});

$(document).on('click', '#save-field-btn', function(e) {

	var additionalFieldId = $('#additional-field-record_id').val();
	
	$('#additional-field-form').hide();
	var position = availableFields.indexOf(Number(additionalFieldId));
	availableFields.splice(position,1);
	
	$.get('{$urlShowAdditionalField}', { 'additional_field_id' : additionalFieldId }, function(data) {
		$('#additional-fields').append(data);
	});
	
	if (availableFields.length > 0) {
		$('#add-field-btn').show();
	}
	e.preventDefault();
	
});

$(document).on('click', '.remove-field-btn', function(e) {

	var availableField = $(this).attr("data-field-id");
	$(this).parent().hide();
	$(this).parent().html('');
	availableFields.push(availableField);
	
	if (availableFields.length > 0) {
		$('#add-field-btn').show();
	}
	
	e.preventDefault();
	
});

EOT_JS
			); ?>
            <div id="additional-fields">
				<?php $model->renderAdditionalFields(); ?>
            </div>

            <div id="additional-field-form"></div>

			<?php
			if (count($availableFields)) {
				echo Html::a(Yii::t('back', 'Add additional field'), '#!', [
					'id' => 'add-field-btn',
					'class' => 'btn btn-default'
				]);
			} ?>
        </div>

		<?= $form->field($model, 'layout_id')->dropDownList($model->getLayoutListOptions()) ?>

		<?= $form->field($model, 'order_time')->widget( DateTimePicker::className(), [
            'convertFormat' => true,
			'pluginOptions' => [
                'autoclose' => true,
				'format' => 'dd.MM.y HH.mm',
			],
			'options' => [
				'class' => 'form-control'
			]
		]) ?>

		<div class="form-group">
			<?= Html::submitButton($model->scenario == 'create' ? Yii::t('back', 'Create') : Yii::t('back', 'Update'), [
				'class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
