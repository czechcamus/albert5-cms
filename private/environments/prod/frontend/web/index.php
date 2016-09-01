<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require( __DIR__ . '/../private/vendor/autoload.php' );
require( __DIR__ . '/../private/vendor/yiisoft/yii2/Yii.php' );
require( __DIR__ . '/../private/common/config/bootstrap.php' );
require( __DIR__ . '/../private/frontend/config/bootstrap.php' );

$config = yii\helpers\ArrayHelper::merge(
	require( __DIR__ . '/../private/common/config/main.php' ),
	require( __DIR__ . '/../private/common/config/main-local.php' ),
	require( __DIR__ . '/../private/frontend/config/main.php' ),
	require( __DIR__ . '/../private/frontend/config/main-local.php' )
);

$application = new yii\web\Application($config);
$application->run();
