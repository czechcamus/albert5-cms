<?php
/* @var $this yii\web\View */
/* @var $content string */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $articleContent \frontend\models\ArticleContent */

use frontend\assets\basic\BasicContentAsset;
use frontend\components\CategoryArticlesList;
use frontend\models\MenuContent;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

BasicContentAsset::register( $this );

$menuContent    = $this->params['menuContent'];
$articleContent = $this->params['articleContent'];
$breadcrumbs    = MenuContent::getBreadCrumbs( $menuContent->id, [], true );
$windowOpen     = 'javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;';

$this->beginContent( $this->theme->getPath( 'layouts/main.php' ) ); ?>

	<section>
		<div class="container">
			<div class="page-title">
				<h2>
					<?php
					if ( $breadcrumbs ) {
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
					<?= $articleContent->title; ?>
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
					<?= CategoryArticlesList::widget( [
						'categoryId' => $menuContent->category->id,
						'articleId'  => $articleContent->id,
						'itemsCount' => 5,
						'title' => '<i class="material-icons right">view_headline</i>' . Yii::t( 'front', 'Recent articles in category' )
					] ); ?>
					<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
						'url' => Url::current([], true)
					] ); ?>
				</div>
			</div>
		</div>
	</section>

<?php $this->endContent(); ?>