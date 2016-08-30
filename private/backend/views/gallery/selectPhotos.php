<?php

use backend\assets\FormAsset;
use common\models\Gallery;
use common\models\LanguageRecord;
use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model backend\models\GalleryAddPhotosForm */
/* @var $gallery common\models\Gallery */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $form yii\bootstrap\ActiveForm */

$session = Yii::$app->session;
if ( ! $session['language_id'] ) {
	$session['language_id'] = LanguageRecord::getMainLanguageId();
}

$gallery                       = Gallery::findOne( $model->item_id );
$this->title                   = Yii::t( 'back', 'add photos into gallery' );
$this->params['breadcrumbs'][] = [ 'label' => Yii::t( 'back', 'Galleries' ), 'url' => [ 'gallery/index' ] ];
$this->params['breadcrumbs'][] = [
	'label' => $gallery->title . ' - ' . Yii::t( 'back', 'photos in gallery' ),
	'url'   => [ 'gallery/photos', 'id' => $model->item_id ]
];
$this->params['breadcrumbs'][] = $this->title;

FormAsset::register( $this );
?>

<div>

	<h1><?= Html::encode( $this->title ) ?></h1>

	<?php $form = ActiveForm::begin( [
		'fieldClass' => ActiveField::className()
	] ); ?>

	<div class="form-group">
		<?= Html::checkbox('selectAll', null, [
			'class' => 'selectAll'
		]) . ' ' . Yii::t('back', 'select all images on this page'); ?>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<?= ListView::widget( [
				'dataProvider' => $dataProvider,
				'itemView'     => '_photo',
				'viewParams'   => compact( 'form' ),
				'layout'       => "<div class=\"row\">{items}</div>\n{pager}"
			] ); ?>
		</div>
	</div>

	<div class="form-group">
		<?= Html::submitButton( Yii::t( 'back', 'Add' ), [
			'class' => 'btn btn-success'
		] ) ?>
		<?= Html::a(
			Yii::t( 'back', 'Close' ), [ 'gallery/photos', 'id' => $model->item_id ],
			[
				'class' => 'btn btn-default'
			]
		) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
