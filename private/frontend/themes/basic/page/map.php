<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use frontend\models\MenuContent;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;
use yii\helpers\Html;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page                        = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'page-content';

$data = [
	['title' => Yii::t('front', 'Aquapark of Dačice'), 'lat' => 49.083191, 'lng' => 15.422774, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['poolMenuId'])],
	['title' => Yii::t('front', 'Sports Hall'), 'lat' => 49.082224, 'lng' => 15.433216, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['hallMenuId'])],
	['title' => Yii::t('front', 'Town Stadium'), 'lat' => 49.084096, 'lng' => 15.434793, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['stadiumMenuId'])],
	['title' => Yii::t('front', 'Skittle-alley'), 'lat' => 49.081995, 'lng' => 15.433613, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['skittleMenuId'])],
	['title' => Yii::t('front', 'Playground in the Boženy Němcové Street'), 'lat' => 49.083391, 'lng' => 15.426961, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground in the Bratrská Street'), 'lat' => 49.082153, 'lng' => 15.434847, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground in the Dlouhá Street'), 'lat' => 49.082818, 'lng' => 15.423321, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground Nivy'), 'lat' => 49.080432, 'lng' => 15.419617, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground in the Vápovská Street'), 'lat' => 49.079634, 'lng' => 15.441433, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground Za školou'), 'lat' => 49.074403, 'lng' => 15.442875, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground in the Chateau park'), 'lat' => 49.077979, 'lng' => 15.430677, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundTownMenuId'])],
	['title' => Yii::t('front', 'Playground in the Bílkov Village'), 'lat' => 49.084297, 'lng' => 15.478046, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Borek Village'), 'lat' => 49.074553, 'lng' => 15.460972, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Dolní Němčice Village'), 'lat' => 49.093647, 'lng' => 15.395012, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Hostkovice Village'), 'lat' => 49.081998, 'lng' => 15.383993, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Hradišťko Village'), 'lat' => 49.059376, 'lng' => 15.439910, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Chlumec Village'), 'lat' => 49.051084, 'lng' => 15.462888, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Lipolec Village'), 'lat' => 49.086869, 'lng' => 15.357986, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Malý Pěčín Village'), 'lat' => 49.107628, 'lng' => 15.458102, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Prostřední Vydří Village'), 'lat' => 49.123202, 'lng' => 15.412280, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
	['title' => Yii::t('front', 'Playground in the Velký Pěčín Village'), 'lat' => 49.119018, 'lng' => 15.450244, 'url' => MenuContent::getMenuUrlFromId( Yii::$app->params[ Yii::$app->language ]['playgroundPlacesMenuId'])],
];

$center = new LatLng([
	'lat' => 49.091067,
	'lng' => 15.421259
]);
$map = new Map([
	'width' => '100%',
	'height' => 800,
	'center' => $center,
	'zoom' => 13
]);

foreach ( $data as $item ) {
	$coord = new LatLng([
		'lat' => $item['lat'],
		'lng' => $item['lng']
	]);
	$marker = new Marker([
		'position' => $coord,
		'title' => $item['title']
	]);
	$marker->attachInfoWindow(
		new InfoWindow([
			'content' => isset($item['url']) ? Html::a($item['title'], $item['url']) : $item['title']
		])
	);
	$map->addOverlay($marker);
}

echo '<div class="row"><div class="col s12">';
echo $map->display();
echo '</div></div>';

if ( $page->image ) {
	echo DisplayImage::widget( [
		'options'  => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image'    => $page->image->filename
	] );
}

if ( $page->perex ) {
	echo '<div class="perex">' . $page->perex . '</div>';
}

if ( $page->description ) {
	echo '<div class="description">';
	echo FrontEndHelper::parseContent( $page->description );
	echo '</div>';
}