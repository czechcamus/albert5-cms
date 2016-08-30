<?php
/* @var $this yii\web\View */
/* @var $content string */
/* @var $menuContent \frontend\models\MenuContent */

use frontend\assets\basic\BasicContentAsset;
use frontend\components\SiblingMenus;
use frontend\models\MenuContent;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

BasicContentAsset::register( $this );

$menuContent = $this->params['menuContent'];
$breadcrumbs = MenuContent::getBreadCrumbs($menuContent->id);

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
					<div class="row">
						<div class="col s12">
							<?= $content; ?>
						</div>
					</div>
				</div>
				<div class="col s12 m4">
					<?php if ( $menuContent->parentItem ) {
						echo SiblingMenus::widget( [
							'parentMenuItemId'  => $menuContent->parent_id,
							'currentMenuItemId' => $menuContent->id,
							'parentMenuTitle'   => $menuContent->parentItem->title
						] );
					} ?>
					<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
						'url' => Url::current([], true)
					] ); ?>
				</div>
			</div>
		</div>
	</section>
<?php $this->endContent(); ?>