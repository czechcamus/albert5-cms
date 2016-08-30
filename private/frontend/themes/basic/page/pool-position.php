<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page                        = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'pool-position-content';

$data = [
	['title' => Yii::t('front', 'Aquapark of Dačice'), 'lat' => 49.083191, 'lng' => 15.422774]
];

$center = new LatLng([
	'lat' => 49.083191,
	'lng' => 15.422774
]);
$map = new Map([
	'width' => '100%',
	'height' => 400,
	'center' => $center,
	'zoom' => 16
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
			'content' => $item['title']
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