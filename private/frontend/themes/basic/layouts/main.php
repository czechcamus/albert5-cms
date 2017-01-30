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
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode( Yii::$app->name . ': ' . $this->title ) ?></title>
	<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
	<?php $this->head() ?>
</head>
<body class="empty">
<?php $this->beginBody() ?>

<!--[if lt IE 7]>
<p class="chromeframe">
	<?= Yii::t( 'front', 'You have really very <strong>old</strong> web browser. Please <a
	href="http://browsehappy.com/">upgrade</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate
	Google Chrome Frame</a> for better experinece.' ); ?>
</p>
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
	<nav role="navigation">
		<div class="container">
			<div id="search-btn" class="right hide-on-med-and-down"><i
					class="material-icons small waves-effect waves-light tooltipped"
					data-position="bottom" data-delay="50"
					data-tooltip="<?= Yii::t( 'front', 'show - hide search window' ); ?>">search</i>
			</div>
			<div class="nav-wrapper">
				<?= Html::a( Yii::$app->name, Yii::$app->homeUrl, [
					'class' => 'brand-logo'
				] ) ?>
				<a href="#" data-activates="mobile-navigation" class="button-collapse"><i
						class="material-icons">menu</i></a>
				<?php
				$languageId = FrontEndHelper::getLanguageIdFromAcronym();
				$menuId     = FrontEndHelper::getMenuIdFromTextId( 'mainmenu' );
				$menuItems  = MenuContent::getItemsTree( 'mainmenu', null, 'dropdown' );
				echo Menu::widget( [
					'options'         => [
						'class' => 'right hide-on-med-and-down'
					],
					'activateParents' => true,
					'encodeLabels'    => false,
					'items'           => $menuItems
				] );
				$sideMenuItems = MenuContent::getItemsTree( 'mainmenu', null, 'collapsible' );
				echo Menu::widget( [
					'options'         => [
						'id' => 'mobile-navigation',
						'class' => 'side-nav collapsible',
						'data-collapsible' => 'accordion'
					],
					'activateParents' => true,
					'encodeLabels'    => false,
					'items'           => $sideMenuItems
				] ); ?>
			</div>
		</div>
	</nav>
</header>

<main class="page-row page-row-expanded">
	<div class="container no-pad-bot" style="position: relative;">
		<div id="search-form-box" style="display: none;">
			<h3><?= Yii::t( 'front', 'Search this site' ); ?></h3>
			<?= Search::widget(); ?>
		</div>
		<?= $content ?>
	</div>
</main>

<footer class="page-row page-footer">
	<div class="container">
		<div class="row">
			<div class="col l6 s12">
				<h5>Company Bio</h5>
				<p>We are a team of college students working on this project like it's
					our full time job. Any amount would help support and continue development on this project and is
					greatly appreciated.</p>
			</div>
			<div class="col l3 s12">
				<h5>Settings</h5>
				<ul>
					<li><a href="#!">Link 1</a></li>
					<li><a href="#!">Link 2</a></li>
					<li><a href="#!">Link 3</a></li>
					<li><a href="#!">Link 4</a></li>
				</ul>
			</div>
			<div class="col l3 s12">
				<h5>Connect</h5>
				<ul>
					<li><a href="#!">Link 1</a></li>
					<li><a href="#!">Link 2</a></li>
					<li><a href="#!">Link 3</a></li>
					<li><a href="#!">Link 4</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			<div class="row">
				<div class="col s12 m4">
					&copy; <?= Yii::$app->params['webOwner'] . ' ' . date( 'Y' ) ?>
				</div>
				<div class="col s12 m4 hide-on-med-and-down center">
					<?= Yii::powered() ?>
				</div>
				<div class="col s12 m4 hide-on-med-and-down right-align">
					Webdesign by <a href="http://www.camus.cz">C@mus</a>
				</div>
			</div>

		</div>
	</div>
</footer>

<?= CookieWidget::widget( [
	'message'   => Yii::t( 'front', 'This website uses cookies to ensure you get the best experience on our website.' ),
	'dismiss'   => Yii::t( 'front', 'Got It' ),
	'learnMore' => Yii::t( 'front', 'More info' ),
	'link'      => Url::to( [ 'site/content', 'id' => Yii::$app->params[ Yii::$app->language ]['cookiePolicyId'] ] ),
	'theme'     => '/basic-assets/vendor/cookieconsent/css/cookieconsent-dark-bottom.css'
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
