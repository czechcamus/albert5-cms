<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require( __DIR__ . '/../../private/vendor/autoload.php' );
require( __DIR__ . '/../../private/vendor/yiisoft/yii2/Yii.php' );
require( __DIR__ . '/../../private/common/config/bootstrap.php' );
require( __DIR__ . '/../../private/backend/config/bootstrap.php' );


$config = require(__DIR__ . '/../../private/tests/codeception/config/backend/acceptance.php');

(new yii\web\Application($config))->run();
