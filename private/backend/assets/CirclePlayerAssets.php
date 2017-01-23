<?php

namespace backend\assets;

use yii\web\AssetBundle;

class CirclePlayerAssets extends AssetBundle
{

    public $sourcePath = '@app/assets';
    public $css = [
        'css/circle-player.css',
    ];
    public $depends = ['xj\jplayer\skins\CircleAssets'];

}
