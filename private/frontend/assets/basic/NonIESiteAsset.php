<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 17.8.2015
 * Time: 16:27
 */

namespace frontend\assets\basic;


use yii\web\AssetBundle;

class NonIESiteAsset extends AssetBundle
{
	public $basePath = '@webroot/basic-assets';
	public $baseUrl = '@web/basic-assets';
	public $css = [
		'vendor/animate/css/animate.min.css',
		'vendor/pe-icon-social/css/pe-icon-social.css',
		'css/site.css'
	];
	public $js = [
		'vendor/materialize/js/materialize.min.js',
		'js/site.js'
	];
	public $depends = [
		'frontend\\assets\\AppAsset'
	];
}