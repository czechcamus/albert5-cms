<?php
/* @var $this yii\web\View */
/* @var $content string */

use frontend\assets\basic\HomeAsset;
use frontend\components\AdditionalField;
use frontend\components\Forecast;
use frontend\components\homepage\InvitationsActualities;
use frontend\components\Weather;
use frontend\models\MenuContent;
use yii\helpers\Html;

HomeAsset::register( $this );
?>

<!--suppress ALL -->

<div class="parallax-container valign-wrapper">
	<div class="container main valign">
		<div class="row">
			<div class="col s12">
				<h1>Sportoviště Dačice</h1>
			</div>
		</div>
	</div>
	<div class="parallax">
		<?= Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/parallax.jpg' ); ?>
	</div>
</div>

<?= InvitationsActualities::widget( [
	'itemsCount'     => 2,
	'columnsCount'   => 2,
	'wordsCount'     => 10,
	'maxImageWidth'  => 400,
	'imageEdgeRatio' => 0.5
] ); ?>

<div class="section third-color">
	<div class="container section1">
		<div class="row">
			<div class="col s12 m4">
				<div class="card">
					<div class="card-image waves-effect waves-block waves-light">
						<?= Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/small-koupaliste.jpg', [
							'class' => 'activator'
						] ); ?>
					</div>
					<div class="card-content">
						<span class="card-title activator grey-text text-darken-4"><?= Yii::t( 'front',
								'Aquapark of Dačice' ); ?><i class="material-icons right">more_vert</i></span>
					</div>
					<div class="card-action">
						<?= Html::a( Yii::t( 'front', 'more information' ),
							MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['poolMenuId'] ) ); ?>
					</div>
					<div class="card-reveal">
						<span class="card-title grey-text text-darken-4"><?= Yii::t( 'front', 'Aquapark of Dačice' ); ?>
							<i class="material-icons right">close</i></span>
						<p><?= Yii::t( 'front',
								'The aquapark of Dačice offers swimming and leisure pool, water slide, a paddling pool and other attractions.' ); ?></p>
					</div>
				</div>
			</div>
			<div class="col s12 m4">
				<div class="card">
					<div class="card-image waves-effect waves-block waves-light">
						<?= Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/small-hala.jpg', [
							'class' => 'activator'
						] ); ?>
					</div>
					<div class="card-content">
						<span class="card-title activator grey-text text-darken-4"><?= Yii::t( 'front',
								'Sports Hall' ); ?><i class="material-icons right">more_vert</i></span>
					</div>
					<div class="card-action">
						<?= Html::a( Yii::t( 'front', 'more information' ),
							MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['hallMenuId'] ) ); ?>
					</div>
					<div class="card-reveal">
						<span class="card-title grey-text text-darken-4"><?= Yii::t( 'front', 'Sports Hall' ); ?><i
								class="material-icons right">close</i></span>
						<p><?= Yii::t( 'front',
								'Sports hall conceals a large hall and two smaller gyms with polyurethane floor and a gym with floorboards.' ); ?></p>
					</div>
				</div>
			</div>
			<div class="col s12 m4">
				<div class="card">
					<div class="card-image waves-effect waves-block waves-light">
						<?= Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/small-sportoviste.jpg', [
							'class' => 'activator'
						] ); ?>
					</div>
					<div class="card-content">
						<span class="card-title activator grey-text text-darken-4"><?= Yii::t( 'front',
								'Other Sports Venues' ); ?><i class="material-icons right">more_vert</i></span>
					</div>
					<div class="card-action">
						<?= Html::a( Yii::t( 'front', 'more information' ),
							MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['othersMenuId'] ) ); ?>
					</div>
					<div class="card-reveal">
						<span class="card-title grey-text text-darken-4"><?= Yii::t( 'front',
								'Other Sports Venues' ); ?><i class="material-icons right">close</i></span>
						<p><?= Yii::t( 'front',
								'Sports stadium, bowling alley, skate park, playground, pitch with artificial turf - all found in or near of town.' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="section  grey-section">
	<div class="container section2" style="opacity: 0">
		<div class="row">
			<div class="col s12 m4">
				<div class="info-important">
					<i class="material-icons medium">pool</i>
					<h4><?= Yii::t('front', 'aquapark'); ?></h4>
					<p class="status">
						<?= AdditionalField::widget([
							'pageId' => Yii::$app->params[Yii::$app->language]['poolPageId'],
							'addFieldId' => Yii::$app->params[Yii::$app->language]['statusAddFieldId'],
							'viewName' => 'todayStatus'
						]); ?><br />
						<?= AdditionalField::widget([
							'pageId' => Yii::$app->params[Yii::$app->language]['poolPageId'],
							'addFieldId' => Yii::$app->params[Yii::$app->language]['watterTempAddFieldId'],
						]); ?>
					</p>
					<?= Html::a( '<i class="material-icons right">forward</i>' . Yii::t( 'front', 'more information' ),
						MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['poolMenuId'] ), ['class' => 'btn'] ); ?>
				</div>
			</div>
			<div class="col s12 m4">
				<div class="info-important">
					<i class="material-icons medium">cloud</i>
					<h4><?= Yii::t('front', 'weather'); ?></h4>
					<p>
						<?= Weather::widget(
							[ 'viewName' => 'hpWeather' ]
						); ?>
						<br>
						<?= Forecast::widget(
							[ 'viewName' => 'hpForecast' ]
						); ?>
					</p>
					<?= Html::a( '<i class="material-icons right">forward</i>' . Yii::t( 'front', 'forecast' ),
						MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['weatherMenuId'] ), ['class' => 'btn'] ); ?>
				</div>
			</div>
			<div class="col s12 m4">
				<div class="info-important">
					<i class="material-icons medium">place</i>
					<h4><?= Yii::t('front', 'map of sports'); ?></h4>
					<p><?= Yii::t('front', 'You can view a map<br /> with the location of sports venues.'); ?></p>
					<?= Html::a( '<i class="material-icons right">forward</i>' . Yii::t( 'front', 'view map' ),
						MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['mapMenuId'] ), ['class' => 'btn'] ); ?>
				</div>
			</div>
<!--
			<div class="col s12 m4">
				<div class="info-important">
					<i class="material-icons medium">event</i>
					<h4>rezervace</h4>
					<p>Můžete si rezervovat čas v některé<br/>
						z tělocvičen sportovní haly</p>
					<a href="#!" class="btn"><i class="material-icons right">forward</i><?php /* echo Yii::t( 'front',
							'booking form' ); */?></a>
				</div>
			</div>
-->
		</div>
	</div>
</div> 