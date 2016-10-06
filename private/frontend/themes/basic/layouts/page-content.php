<?php
/* @var $this yii\web\View */
/* @var $content string */
/* @var $menuContent \frontend\models\MenuContent */

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
						$pattern = "/(.*) (<i.*i>)/i";
						$replacement = "$1";
						echo SiblingMenus::widget( [
							'title'             => '<i class="material-icons right">arrow_forward</i>' . Yii::t( 'front',
									'Next pages in menu' ),
							'parentMenuItemId'  => $menuContent->parent_id,
							'currentMenuItemId' => $menuContent->id,
							'parentMenuTitle'   => preg_replace($pattern, $replacement, $menuContent->parentItem->title)
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