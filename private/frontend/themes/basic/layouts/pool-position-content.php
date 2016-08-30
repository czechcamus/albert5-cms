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

	<div id="fb-root"></div>
	<script>
		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v2.5";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

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
						'title'            => ucfirst( $this->title )
					] ); ?>
					<?php if ( $menuContent->parentItem ) {
						echo SiblingMenus::widget( [
							'parentMenuItemId'  => $menuContent->parent_id,
							'currentMenuItemId' => $menuContent->id,
							'parentMenuTitle'   => $menuContent->parentItem->title
						] );
					} ?>
					<div class="fb-page" data-href="https://www.facebook.com/koupalistedacice" data-tabs="timeline" data-height="600" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
						<div class="fb-xfbml-parse-ignore">
							<blockquote cite="https://www.facebook.com/koupalistedacice"><a href="https://www.facebook.com/koupalistedacice">Koupaliště Dačice</a></blockquote>
						</div>
					</div>
					<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
						'url' => Url::current([], true)
					] ); ?>
				</div>
			</div>
		</div>
	</section>

<?php $this->endContent(); ?>