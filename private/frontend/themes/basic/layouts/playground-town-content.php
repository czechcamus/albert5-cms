<?php
/* @var $this yii\web\View */
/* @var $content string */
/* @var $menuContent \frontend\models\MenuContent */

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use frontend\assets\basic\BasicContentAsset;
use frontend\components\SiblingMenus;
use frontend\components\SubMenus;
use frontend\models\MenuContent;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

BasicContentAsset::register( $this );

$menuContent = $this->params['menuContent'];
$breadcrumbs = MenuContent::getBreadCrumbs($menuContent->id);
$windowOpen  = 'javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;';

$data = [
	['title' => Yii::t('front', 'Playground in the Boženy Němcové Street'), 'lat' => 49.083391, 'lng' => 15.426961],
	['title' => Yii::t('front', 'Playground in the Bratrská Street'), 'lat' => 49.082153, 'lng' => 15.434847],
	['title' => Yii::t('front', 'Playground in the Dlouhá Street'), 'lat' => 49.082818, 'lng' => 15.423321],
	['title' => Yii::t('front', 'Playground Nivy'), 'lat' => 49.080432, 'lng' => 15.419617],
	['title' => Yii::t('front', 'Playground in the Vápovská Street'), 'lat' => 49.079634, 'lng' => 15.441433],
	['title' => Yii::t('front', 'Playground Za školou'), 'lat' => 49.074403, 'lng' => 15.442875],
	['title' => Yii::t('front', 'Playground in the Chateau park'), 'lat' => 49.077979, 'lng' => 15.430677],
];

$center = new LatLng([
	'lat' => 49.079146,
	'lng' => 15.433054
]);
$map = new Map([
	'width' => '100%',
	'height' => 400,
	'center' => $center,
	'zoom' => 14
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

$this->beginContent( $this->theme->getPath( 'layouts/main.php' ) ); ?>

	<section>
		<div class="container">
			<div class="page-title">
				<h2>
					<?php
					if ($breadcrumbs) {
						echo Breadcrumbs::widget( [
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
						] );
					} ?>
					<?= ucfirst( $this->title ); ?>
				</h2>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="row content">
				<div class="col s12 m8">
					<?= $content; ?>
				</div>
				<div class="col s12 m4">
					<?= SubMenus::widget( [
						'parentMenuItemId' => $menuContent->id,
						'title' => '<i class="material-icons right">arrow_downward</i>' . ucfirst( $this->title )
					] ); ?>
					<?php if ( $menuContent->parentItem ) {
						echo SiblingMenus::widget( [
							'title' => '<i class="material-icons right">arrow_forward</i>' . Yii::t('front', 'Next pages in menu'),
							'parentMenuItemId'  => $menuContent->parent_id,
							'currentMenuItemId' => $menuContent->id,
							'parentMenuTitle'   => $menuContent->parentItem->title
						] );
					} ?>
					<?= $map->display(); ?>
					<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
						'url' => Url::current([], true)
					] ); ?>
				</div>
			</div>
		</div>
	</section>

<?php $this->endContent(); ?>