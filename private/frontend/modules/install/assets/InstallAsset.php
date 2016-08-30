<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 12:57
 */

namespace frontend\modules\install\assets;


use yii\web\AssetBundle;

class InstallAsset extends AssetBundle
{
	public $sourcePath = '@frontend/modules/install/assets';
	public $css = [];
	public $js = [];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset'
	];
}