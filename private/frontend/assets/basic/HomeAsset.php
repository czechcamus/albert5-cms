<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 27.8.2015
 * Time: 15:36
 */

namespace frontend\assets\basic;


use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
	public $basePath = '@webroot/basic-assets';
	public $baseUrl = '@web/basic-assets';
	public $css = [
		'css/home.css'
	];
	public $js = [
		'js/home.js'
	];
	public $depends = [
		'frontend\\assets\\basic\\SiteAsset'
	];
}