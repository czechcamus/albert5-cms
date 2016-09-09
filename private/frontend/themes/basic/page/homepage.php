<?php
/* @var $this yii\web\View */
/* @var $menuContent \common\models\MenuItemRecord */

use frontend\assets\basic\HomeAsset;

HomeAsset::register( $this );

$this->title = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page = $menuContent->content;
?>

<div class="section">
	<h1 class="header center">
		<?= $page->title ?>
	</h1>
	<div class="row center">
		<h5 class="col s12 light"><?= $page->perex ?></h5>
		<p class="col s12"><?= $page->description ?></p>
	</div>

</div>

<div class="section">
	<!--   Icon Section   -->
	<div class="row">
		<div class="col s12 m4">
			<div class="icon-block">
				<h2 class="center"><i class="material-icons">flash_on</i></h2>
				<h5 class="center">Speeds up development</h5>
				<p class="light">We did most of the heavy lifting for you to provide a default stylings that incorporate our custom components. Additionally, we refined animations and transitions to provide a smoother experience for developers.</p>
			</div>
		</div>

		<div class="col s12 m4">
			<div class="icon-block">
				<h2 class="center"><i class="material-icons">group</i></h2>
				<h5 class="center">User Experience Focused</h5>
				<p class="light">By utilizing elements and principles of Material Design, we were able to create a framework that incorporates components and animations that provide more feedback to users. Additionally, a single underlying responsive system across all platforms allow for a more unified user experience.</p>
			</div>
		</div>

		<div class="col s12 m4">
			<div class="icon-block">
				<h2 class="center"><i class="material-icons">settings</i></h2>
				<h5 class="center">Easy to work with</h5>
				<p class="light">We have provided detailed documentation as well as specific code examples to help new users get started. We are also always open to feedback and can answer any questions a user may have about Materialize.</p>
			</div>
		</div>
	</div>
</div>