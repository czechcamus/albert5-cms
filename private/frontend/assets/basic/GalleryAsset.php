<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.9.2015
 * Time: 16:55
 */

namespace frontend\assets\basic;


use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle
{
	public $basePath = '@webroot/basic-assets';
	public $baseUrl = '@web/basic-assets';
	public $css = [
		'css/blueimp-gallery.min.css',
		'css/gallery.css'
	];
	public $js = [
		'js/jquery.blueimp-gallery.min.js',
		'js/gallery.js'
	];
	public $depends = [
		'frontend\\assets\\basic\\SiteAsset'
	];

}