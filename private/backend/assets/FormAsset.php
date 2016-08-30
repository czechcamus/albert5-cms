<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 18.2.2015
 * Time: 23:06
 */

namespace backend\assets;


use yii\web\AssetBundle;

class FormAsset extends AssetBundle
{
	public $sourcePath = '@app/assets';
	public $js = [
		'js/form.js'
	];
	public $depends = [
		'backend\\assets\\BackendAsset'
	];

}