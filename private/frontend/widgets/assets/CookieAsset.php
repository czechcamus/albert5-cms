<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.11.2015
 * Time: 22:15
 */

namespace frontend\widgets\assets;


class CookieAsset extends \cinghie\cookieconsent\assets\CookieAsset
{

	/**
	 * @inherit
	 */
	public $depends = [
		'yii\web\YiiAsset',
	];
}