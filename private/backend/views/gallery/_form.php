<?php

use backend\assets\FormAsset;
use backend\models\GalleryForm;
use backend\utilities\CKEditor;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model GalleryForm */
/* @var $form yii\bootstrap\ActiveForm */

FormAsset::register( $this );
?>

<div class="row">


	<?php $form = ActiveForm::begin( [
		'fieldClass'  => ActiveField::className(),
	] ); ?>

    <div class="col-sm-12 col-md-9">

		<?= $form->field( $model, 'title' )->textInput( [ 'maxlength' => 255 ] ) ?>

		<?= $form->field( $model, 'description' )->widget( CKEditor::className(), [
			'options'       => [ 'rows' => 4 ],
			'preset'        => 'custom',
			'clientOptions' => [
				'height'        => 400,
				'toolbarGroups' => [
					[ 'name' => 'clipboard', 'groups' => [ 'mode', 'undo', 'selection', 'clipboard', 'doctools' ] ],
					[ 'name' => 'styles' ],
					[ 'name' => 'editing', 'groups' => [ 'tools' ] ],
					'/',
					[ 'name' => 'basicstyles', 'groups' => [ 'basicstyles', 'cleanup' ] ],
					[ 'name' => 'paragraph', 'groups' => [ 'list', 'indent', 'align', 'blocks' ] ],
					[ 'name' => 'links' ],
					[ 'name' => 'insert' ],
					[ 'name' => 'others' ],
				],
				'removeButtons' => 'Templates,CreateDiv,Styles,Font,FontSize,Smiley,Iframe,ShowBlocks,Flash,PageBreak,Gallery,Sound,Youtube,Poll,Article'
			]
		] ) ?>

    </div>

    <div class="col-sm-12 col-md-3">

		<?= /** @noinspection PhpUndefinedMethodInspection */
		$form->field( $model, 'boxes' )->inline()->checkboxList( [
			GalleryForm::PROPERTY_PUBLIC => Yii::t( 'back', 'Public' ),
			GalleryForm::PROPERTY_ACTIVE => Yii::t( 'back', 'Active' )
		], [
			'id' => 'modal-boxes'
		] ) ?>

        <div class="form-group">
			<?= Html::submitButton( $model->scenario == 'create' ? Yii::t( 'back', 'Create' ) : Yii::t( 'back',
				'Update' ), [
				'class' => $model->scenario == 'create' ? 'btn btn-success' : 'btn btn-primary'
			] ) ?>
        </div>

    </div>

	<?php ActiveForm::end(); ?>

</div>
