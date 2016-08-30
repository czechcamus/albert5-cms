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
		'https://fonts.googleapis.com/css?family=Hind:400,300,500&subset=latin,latin-ext',
		'css/animate.min.css',
		'css/weather-icons.min.css',
		'css/weather-icons-wind.min.css',
		'css/pe-icon-social.css',
		'css/site.css'
	];
	public $js = [
		'js/site.js'
	];
	public $depends = [
		'frontend\\assets\\AppAsset'
	];
}