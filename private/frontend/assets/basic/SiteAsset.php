<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 17.8.2015
 * Time: 16:27
 */

namespace frontend\assets\basic;


use yii\web\AssetBundle;

class SiteAsset extends AssetBundle
{
	public $basePath = '@webroot/basic-assets';
	public $baseUrl = '@web/basic-assets';
	public $css = [
		'css/ie.css'
	];
	public $cssOptions = [
		'condition' => 'IE'
	];
	public $depends = [
		'frontend\\assets\\basic\\NonIESiteAsset'
	];
}