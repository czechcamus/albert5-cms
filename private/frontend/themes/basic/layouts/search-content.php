<?php
/* @var $this yii\web\View */
/* @var $content string */

use frontend\assets\basic\BasicContentAsset;
use yii\widgets\Breadcrumbs;

BasicContentAsset::register( $this );

$breadcrumbs = [
	[ 'label' => $this->title ]
];

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
				<div class="col s12">
					<?= $content; ?>
				</div>
			</div>
		</div>
	</section>

<?php $this->endContent(); ?>