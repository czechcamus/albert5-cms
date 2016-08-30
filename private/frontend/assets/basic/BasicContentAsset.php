<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.9.2015
 * Time: 16:55
 */

namespace frontend\assets\basic;


use yii\web\AssetBundle;

class BasicContentAsset extends AssetBundle
{
	public $basePath = '@webroot/basic-assets';
	public $baseUrl = '@web/basic-assets';
	public $css = [
		'css/basic-content.css'
	];
	public $js = [
	];
	public $depends = [
		'frontend\\assets\\basic\\SiteAsset'
	];

}