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
	['title' => Yii::t('front', 'Town Stadium'), 'lat' => 49.084096, 'lng' => 15.434793]
];

$center = new LatLng([
	'lat' => 49.084096,
	'lng' => 15.434793
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