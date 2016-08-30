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
	['title' => Yii::t('front', 'Playground in the Bílkov Village'), 'lat' => 49.084297, 'lng' => 15.478046],
	['title' => Yii::t('front', 'Playground in the Borek Village'), 'lat' => 49.074553, 'lng' => 15.460972],
	['title' => Yii::t('front', 'Playground in the Dolní Němčice Village'), 'lat' => 49.093647, 'lng' => 15.395012],
	['title' => Yii::t('front', 'Playground in the Hostkovice Village'), 'lat' => 49.081998, 'lng' => 15.383993],
	['title' => Yii::t('front', 'Playground in the Hradišťko Village'), 'lat' => 49.059376, 'lng' => 15.439910],
	['title' => Yii::t('front', 'Playground in the Chlumec Village'), 'lat' => 49.051084, 'lng' => 15.462888],
	['title' => Yii::t('front', 'Playground in the Lipolec Village'), 'lat' => 49.086869, 'lng' => 15.357986],
	['title' => Yii::t('front', 'Playground in the Malý Pěčín Village'), 'lat' => 49.107628, 'lng' => 15.458102],
	['title' => Yii::t('front', 'Playground in the Prostřední Vydří Village'), 'lat' => 49.123202, 'lng' => 15.412280],
	['title' => Yii::t('front', 'Playground in the Velký Pěčín Village'), 'lat' => 49.119018, 'lng' => 15.450244],
];

$center = new LatLng([
	'lat' => 49.079146,
	'lng' => 15.433054
]);
$map = new Map([
	'width' => '100%',
	'height' => 400,
	'center' => $center,
	'zoom' => 11
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