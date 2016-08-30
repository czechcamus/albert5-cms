<?php
/** @var $message string */
/** @var $dismiss string */
/** @var $learnMore string */
/** @var $link string */
/** @var $theme string */
/** @var $container string */
/** @var $path string */
/** @var $domain string */
/** @var $expiryDays integer */

use frontend\widgets\assets\CookieAsset;

// Load Articles Assets
CookieAsset::register($this);
$asset = $this->assetBundles['frontend\widgets\assets\CookieAsset'];

// Create codeJS
$codeJS = "window.cookieconsent_options = { message: '{$message}', dismiss: '{$dismiss}', learnMore: '{$learnMore}', link: '{$link}', theme: '{$theme}', container: '{$container}', path: '{$path}',";
if($domain){
	$codeJS .= " domain: '{$domain}',";
}
$codeJS .= " expiryDays: {$expiryDays} };";

$this->registerJs($codeJS);