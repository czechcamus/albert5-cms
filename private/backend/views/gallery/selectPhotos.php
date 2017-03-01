<?php

use backend\assets\FormAsset;
use common\models\Gallery;
use common\models\LanguageRecord;
use kop\y2sp\ScrollPager;
use yii\bootstrap\ActiveField;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
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
$this->params['loading-text'] = Yii::t('back', 'Synchronizing photos, please wait ...');

FormAsset::register( $this );
?>

<div>

    <h1><?= Html::encode( $this->title ) ?></h1>

	<?php $form = ActiveForm::begin( [
		'fieldClass' => ActiveField::className()
	] ); ?>

    <div class="row" style="margin-bottom: 15px;">
        <div class="col-xs-12 btn-group" data-toggle="buttons">
            <label class="btn btn-primary<?= $session['photosView'] === 'list' ? ' active' : ''; ?>" onclick="$('#loading-box').showLoading();$(location).attr('href', '<?= Url::to(['add-photos', 'id' => $gallery->id, 'photosView' => 'list'], true); ?>');">
                <?= Html::radio('view-option', $session['photosView'] === 'list' ? true : false, [
                    'id' => 'list',
                    'autocomplete' => 'off'
                ]); ?>
                <?= Yii::t( 'back',	'Thumbnails' ); ?>
            </label>
            <label class="btn btn-primary<?= $session['photosView'] === 'grid' ? ' active' : ''; ?>" onclick="$('#loading-box').showLoading();$(location).attr('href', '<?= Url::to(['add-photos', 'id' => $gallery->id, 'photosView' => 'grid'], true); ?>');">
	            <?= Html::radio('view-option', $session['photosView'] === 'grid' ? true : false, [
		            'id' => 'grid',
		            'autocomplete' => 'off'
	            ]); ?>
                <?= Yii::t( 'back', 'List of images' ); ?>
            </label>
        </div>
    </div>


    <div id="thumbs-view" class="row">
        <div class="col-xs-12">
            <div class="form-group">
				<?= Html::checkbox( 'selectAll', null, [
					'class' => 'selectAll'
				] ) . ' ' . Yii::t( 'back', 'select all images on this page' ); ?>
            </div>

			<?php
            if ($session['photosView'] === 'list') {
	            echo ListView::widget( [
		            'dataProvider' => $dataProvider,
		            'itemView'     => '_photo',
		            'viewParams'   => compact( 'form' ),
		            'layout'       => "<div class=\"row\">{items}</div>\n{pager}",
		            'pager' => [
			            'class' => ScrollPager::className(),
			            'item' => '.photo-view',
                        'triggerText' => Yii::t('back', 'Load next images'),
                        'noneLeftText' => ''
		            ]
	            ] );
            } else {
	            echo GridView::widget([
		            'dataProvider' => $dataProvider,
                    'columns' => [
	                    [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'GalleryAddPhotosForm[addedImagesIds]',
                            'header' => false
	                    ],
                        'filename',
                        [
                            'attribute' => 'file_time',
                            'value' => function($model) {
	                            return Yii::$app->formatter->asDatetime($model->file_time);
                            }
                        ]
                    ],
		            'pager' => [
			            'class' => ScrollPager::className(),
			            'container' => '.grid-view tbody',
			            'item' => 'tr',
			            'paginationSelector' => '.grid-view .pagination',
			            'triggerTemplate' => '<tr class="ias-trigger"><td colspan="100%" style="text-align: center"><a style="cursor: pointer">{text}</a></td></tr>',
			            'noneLeftText' => ''
		            ],
	            ]);
            }
            ?>
        </div>
    </div>

    <div class="form-group">
		<?= Html::submitButton( Yii::t( 'back', 'Add' ), [
			'class' => 'btn btn-success'
		] ) ?>
        <span class="show-loading">
		<?= Html::a(
			Yii::t( 'back', 'Close' ), [ 'gallery/photos', 'id' => $model->item_id ],
			[
				'class' => 'btn btn-default'
			]
		) ?>
        </span>
    </div>

	<?php ActiveForm::end(); ?>

</div>
