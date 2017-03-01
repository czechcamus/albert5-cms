<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MyLoadingAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';
    public $css = [
        'css/showLoading.css'
    ];
    public $js = [
        'js/jquery.showLoading.min.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
