<?php
/* @var $this yii\web\View */
/* @var $form frontend\utilities\MaterializeActiveForm */
/* @var $model frontend\models\ContactForm */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use frontend\assets\basic\BasicContentAsset;
use frontend\components\PageContent;
use frontend\utilities\MaterializeActiveForm;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

BasicContentAsset::register( $this );

$this->title = Yii::t( 'front', 'our contacts' );
$breadcrumbs = [
	[ 'label' => $this->title ]
];
$template    = "\n{input}\n{label}\n{hint}\n{error}";

$data = [
	[
		'title' => 'Technické služby Dačice s.r.o.',
		'lat'   => 49.087160,
		'lng'   => 15.434929,
		'url'   => 'http://www.tsdacice.cz'
	]
];

$center = new LatLng( [
	'lat' => $data[0]['lat'],
	'lng' => $data[0]['lng'],
] );
$map    = new Map( [
	'width'  => '100%',
	'height' => 400,
	'center' => $center,
	'zoom'   => 16
] );

foreach ( $data as $item ) {
	$coord  = new LatLng( [
		'lat' => $item['lat'],
		'lng' => $item['lng']
	] );
	$marker = new Marker( [
		'position' => $coord,
		'title'    => $item['title']
	] );
	$marker->attachInfoWindow(
		new InfoWindow( [
			'content' => Html::a( $item['title'], $item['url'] )
		] )
	);
	$map->addOverlay( $marker );
}

?>

<section>
	<div class="container">
		<div class="page-title">
			<h2>
				<?= Breadcrumbs::widget( [
					'homeLink'           => [
						'label' => Yii::t( 'front', 'home' ),
						'url'   => Yii::$app->homeUrl
					],
					'activeItemTemplate' => "<span>{link}</span>",
					'itemTemplate'       => "<span>{link}</span>",
					'links'              => $breadcrumbs,
					'tag'                => 'div',
					'options'            => [
						'class' => 'breadcrumbs'
					]
				] ); ?>
				<?= ucfirst( $this->title ); ?>
			</h2>
		</div>
	</div>
</section>

<section>
	<div class="container">
		<div class="row">
			<div class="col s12">
				<?= $map->display(); ?>
			</div>
		</div>
		<div class="row content">
			<div class="col s12 m8 site-form">

				<?php if ( Yii::$app->session->hasFlash( 'info' ) ): ?>
					<div class="row">
						<div class="col s12 m6">
							<h4>
								<?= Yii::$app->session->getFlash( 'info' ); ?>
							</h4>
						</div>
					</div>
				<?php else: ?>

					<h3><?= Yii::t( 'front', 'Contact form' ); ?></h3>

					<p>
						<?= Yii::t( 'front', 'If you want to send some message to us, you can use next form:' ); ?>
					</p>

					<?php $form = MaterializeActiveForm::begin( [ 'id' => 'site-message' ] ); ?>

					<div class="row">
						<?= $form->field( $model, 'name', [
							'template' => "<i class=\"material-icons prefix\">account_box</i>$template",
							'options'  => [ 'class' => 'input-field col s12' ]
						] ); ?>
					</div>
					<div class="row">
						<?= $form->field( $model, 'email', [
							'template' => "<i class=\"material-icons prefix\">email</i>$template",
							'options'  => [ 'class' => 'input-field col s12' ]
						] ); ?>
					</div>
					<div class="row">
						<?= $form->field( $model, 'message', [
							'template'     => "<i class=\"material-icons prefix\">mode_edit</i>$template",
							'options'      => [ 'class' => 'input-field col s12' ],
							'inputOptions' => [
								'class' => 'materialize-textarea'
							]
						] )->textarea(); ?>
					</div>
					<div class="row">
						<?= $form->field( $model, 'verifyCode', [
							'template' => "{input}\n{label}\n{hint}\n{error}",
							'options'  => [ 'class' => 'input-field col s12 m6' ]
						] )->widget( Captcha::className(), [
							'imageOptions' => [ 'class' => 'captcha-prefix responsive-img' ]
						] ); ?>
					</div>

					<div class="row">
						<div class="col s12">
							<?= Html::submitButton( '<i class="material-icons right">send</i>' . Yii::t( 'front',
									'Submit' ), [
								'class' => 'btn waves-effect waves-light',
								'name'  => 'message-button'
							] ) ?>
						</div>
					</div>
					<?php MaterializeActiveForm::end(); ?>

				<?php endif; ?>

			</div>
			<div class="col s12 m4">
				<div class="side-content">
					<h3><?= Yii::t( 'front', 'Contacts' ); ?></h3>
					<div>
					<?= PageContent::widget( [
						'pageId'   => Yii::$app->params[ Yii::$app->language ]['contactPageId'],
						'viewName' => 'description'
					] ); ?>
					</div>
				</div>
				<div class="side-content">
					<h3><?= Yii::t( 'front', 'Business hours' ); ?></h3>
					<div>
					<?= PageContent::widget( [
						'pageId'   => Yii::$app->params[ Yii::$app->language ]['businessHoursPageId'],
						'viewName' => 'description'
					] ); ?>
					</div>
				</div>
				<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
					'url' => Url::current( [ ], true )
				] ); ?>
			</div>
		</div>
	</div>
</section>