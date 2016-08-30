<?php
/* @var $this yii\web\View */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use frontend\components\PageContent;
use frontend\widgets\GoogleMap;
use yii\helpers\Html;

$data = [
	[
		'lat'   => 49.087160,
		'lng'   => 15.434929,
	]
];

$center = new LatLng( [
	'lat' => $data[0]['lat'],
	'lng' => $data[0]['lng'],
] );
$map    = new GoogleMap( [
	'width'  => 640,
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
	] );
	$map->addOverlay( $marker );
} ?>

<body>

<h1><?= Yii::t('front', 'Contacts'); ?></h1>

<div class="row"><div class="col s12">
<?= Html::img($map->getStaticMapUrl()); ?>
</div></div>

<div>
	<h3><?= Yii::t( 'front', 'Contacts' ); ?></h3>
	<?= PageContent::widget( [
		'pageId'   => Yii::$app->params[ Yii::$app->language ]['contactPageId'],
		'viewName' => 'description'
	] ); ?>
</div>
<div>
	<h3><?= Yii::t( 'front', 'Business hours' ); ?></h3>
	<?= PageContent::widget( [
		'pageId'   => Yii::$app->params[ Yii::$app->language ]['businessHoursPageId'],
		'viewName' => 'description'
	] ); ?>
</div>

</body>