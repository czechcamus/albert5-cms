<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 17.8.2015
 * Time: 16:27
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class ErrorAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'https://fonts.googleapis.com/css?family=Hind:400,300,500&subset=latin,latin-ext',
		'css/error.css'
	];
	public $js = [
	];
	public $depends = [
		'frontend\\assets\\AppAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}