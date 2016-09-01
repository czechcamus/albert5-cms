<?php
use frontend\assets\basic\SiteAsset;
use frontend\components\Search;
use frontend\models\MenuContent;
use frontend\utilities\FrontEndHelper;
use frontend\widgets\CookieWidget;
use raoul2000\widget\scrollup\Scrollup;
use sersid\owlcarousel\Asset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $content string */

SiteAsset::register( $this );
Asset::register( $this );

$this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode( Yii::$app->name . ': ' . $this->title ) ?></title>
	<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
	<?php $this->head() ?>
</head>
<body class="empty">
<?php $this->beginBody() ?>

<!--[if lt IE 7]>
<p class="chromeframe">Máte opravdu hodně <strong>starý</strong> prohlížeč. Prosíme <a href="http://browsehappy.com/">přejděte
	na novou verzi</a> nebo <a href="http://www.google.com/chromeframe/?redirect=true">si aktivujte Google Chrome
	Frame</a> pro lepší zážitek.</p>
<![endif]-->

<div id="preloader-box" class="page-row">
	<div class="preloader-wrapper big active">
		<div class="spinner-layer spinner-red-only">
			<div class="circle-clipper left">
				<div class="circle"></div>
			</div>
			<div class="gap-patch">
				<div class="circle"></div>
			</div>
			<div class="circle-clipper right">
				<div class="circle"></div>
			</div>
		</div>
	</div>

	<div class="preloader-section section-left"></div>
	<div class="preloader-section section-right"></div>
</div>

<header class="page-row">
	<div class="navbar-fixed">
		<nav>
			<div class="container">
				<div id="search-btn" class="right hide-on-med-and-down"><i
						class="material-icons small waves-effect waves-light tooltipped"
						data-position="bottom" data-delay="50"
						data-tooltip="zobrazit / skrýt okno pro vyhledávání">search</i>
				</div>
				<div class="nav-wrapper">
					<?= Html::a( Html::img( Yii::$app->request->baseUrl . '/basic-assets/img/logo.png', [
						'alt' => Yii::$app->name . ' - logo'
					] ), Yii::$app->homeUrl, [
						'class' => 'brand-logo'
					] ) ?>
					<a href="#" data-activates="mobile-navigation" class="button-collapse"><i class="material-icons">menu</i></a>
					<?php
					$languageId = FrontEndHelper::getLanguageIdFromAcronym();
					$menuId     = FrontEndHelper::getMenuIdFromTextId( 'mainmenu' );
					$menuItems  = MenuContent::getItemsTree( $languageId, $menuId );
					echo Menu::widget( [
						'options'         => [
							'class' => 'right hide-on-med-and-down'
						],
						'activateParents' => true,
						'encodeLabels'    => false,
						'items'           => $menuItems
					] );
					$sideMenuItems = MenuContent::getItemsTree( $languageId, $menuId );
					echo Menu::widget( [
						'options'         => [
							'id'    => 'mobile-navigation',
							'class' => 'side-nav'
						],
						'activateParents' => true,
						'encodeLabels'    => false,
						'items'           => $sideMenuItems
					] ); ?>
				</div>
			</div>
		</nav>
	</div>
</header>

<main class="page-row page-row-expanded">
	<div class="container" style="position: relative;">
		<div id="search-form-box" style="display: none;">
			<h3><?= Yii::t( 'front', 'Search this site' ); ?></h3>
			<?= Search::widget(); ?>
		</div>
	</div>

	<?= $content ?>
</main>

<footer class="page-row">
	<div class="container copyright">
		<div class="row" style="margin-bottom: 0">
			<div class="col s12 m4">
				<p>&copy; <?= Yii::$app->params['webOwner'] . ' ' . date( 'Y' ) ?></p>
			</div>
			<div class="col s12 m4 hide-on-med-and-down">
				<p class="center-align"><?= Yii::powered() ?></p>
			</div>
			<div class="col s12 m4 hide-on-med-and-down">
				<p class="right-align">Webdesign by <a href="http://www.camus.cz">C@mus</a></p>
			</div>
		</div>
	</div>
</footer>

<?= CookieWidget::widget( [
	'message'   => Yii::t( 'front', 'This website uses cookies to ensure you get the best experience on our website.' ),
	'dismiss'   => Yii::t( 'front', 'Got It' ),
	'learnMore' => Yii::t( 'front', 'More info' ),
	'link'      => Url::to( [ 'site/content', 'id' => Yii::$app->params[ Yii::$app->language ]['cookiePolicyId'] ] ),
	'theme'     => '/basic-assets/css/cookieconsent-dark-bottom.css'
] ); ?>

<?= Scrollup::widget( [
	'theme'         => Scrollup::THEME_IMAGE,
	'pluginOptions' => [
		'scrollText'        => Yii::t( 'front', 'to top' ),
		'scrollName'        => 'scrollUp',
		'topDistance'       => 400,
		'topSpeed'          => 3000,
		'animation'         => Scrollup::ANIMATION_FADE,
		'animationInSpeed'  => 400,
		'animationOutSpeed' => 400,
		'activeOverlay'     => false
	]
] ); ?>

<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>
