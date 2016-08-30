<?php

use backend\assets\MenuItemFormAsset;
use backend\models\MenuItemForm;
use common\models\Category;
use common\models\MenuItemRecord;
use common\models\Page;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MenuItemForm */
/* @var $form yii\bootstrap\ActiveForm */

MenuItemFormAsset::register($this);
?>

<div>

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldClass' => ActiveField::className(),
        'fieldConfig' => Yii::$app->params['fieldConfig'],
	    'options' => [
		    'id' => 'menu-item-form'
	    ]
    ]); ?>

    <?= $form->field($model, 'web_id')->dropDownList($model->getWebListOptions(),
	    [
		    'prompt' => '-- ' . Yii::t('back', 'Not selected') . ' --',
		    'onchange' => '
		        $.get("' . Url::to(['menu-item/menu-list-options']) . '",
		        {
		            wid: $(this).val()
		        })
		        .done(function(data) {
		            $("#' . Html::getInputId($model, 'menu_id') . '").html(data);
		        });'
	    ]) ?>

	<?= $form->field($model, 'menu_id')->dropDownList([],
		[
			'prompt' => '-- ' . Yii::t('back', 'Not selected') . ' --',
			'onchange' => '
		        $.post("' . Url::to(['menu-item/parent-list-options']) . '",
		        {
		            menu_id: $(this).val()
		        })
		        .done(function(data) {
		            $("#' . Html::getInputId($model, 'parent_id') . '").html(data);
		        });'
		]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList([], ['prompt' => '-- ' . Yii::t('back', 'No parent') . ' --']) ?>

    <?= $form->field($model, 'content_type')->dropDownList([
	    $model->content_type => $model->content_type == MenuItemRecord::CONTENT_PAGE ? Yii::t('back', 'Page') : Yii::t('back', 'Category')
    ]) ?>

    <?= $form->field($model, 'content_id')->dropDownList([
	    $model->content_id => $model->content_type == MenuItemRecord::CONTENT_PAGE ?
		    Page::find()->select('title')->andWhere(['id' => $model->content_id])->scalar() :
		    Category::find()->select('title')->andWhere(['id' => $model->content_id])->scalar()
    ]) ?>

	<?= $form->field($model, 'layout_id')->dropDownList($model->getLayoutListOptions()) ?>

    <?= /** @noinspection PhpUndefinedMethodInspection */
    $form->field($model, 'boxes')->inline()->checkboxList([
        MenuItemForm::PROPERTY_MAIN => Yii::t('back', 'Main'),
        MenuItemForm::PROPERTY_ACTIVE => Yii::t('back', 'Active'),
        MenuItemForm::PROPERTY_PUBLIC => Yii::t('back', 'Public')
    ]) ?>

    <div class="form-group modal-footer">
        <?= Html::submitButton(Yii::t('back', 'Create'), [
            'class' => 'btn btn-success'
        ]) ?>
	    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('back', 'Close'); ?></button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
