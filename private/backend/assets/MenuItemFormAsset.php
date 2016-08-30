<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 16.2.2015
 * Time: 13:34
 */

namespace backend\assets;

use yii\web\AssetBundle;

class MenuItemFormAsset extends AssetBundle
{
	public $sourcePath = '@app/assets';
	public $js = [
		'js/menu-item-form.js'
	];
	public $depends = [
		'backend\\assets\\BackendAsset'
	];
}