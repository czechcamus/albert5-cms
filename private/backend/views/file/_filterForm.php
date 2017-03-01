<?php
/* @var $this yii\web\View */
/* @var $searchModel backend\models\FileSearch */
/* @var $form yii\bootstrap\ActiveForm */

use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="row" style="margin-bottom: 10px">

<?php $form = ActiveForm::begin([
	'fieldClass' => ActiveField::className()
]);	?>

<div class="col-sm-4">
	<?= $form->field($searchModel, 'fileTitle')->textInput(); ?>
</div>

<div class="col-sm-12 form-group">
	<?= Html::submitButton(Yii::t('back', 'Apply filter'), [
		'class' => 'btn btn-primary'
	]); ?>
</div>

<?php ActiveForm::end(); ?>

</div>
