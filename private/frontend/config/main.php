<?php
use kartik\mpdf\Pdf;

$params = array_merge(
    require( __DIR__ . '/../../common/config/params.php' ),
    require( __DIR__ . '/../../common/config/params-local.php' ),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$urlRules = require( __DIR__ . '/url-rules.php');

$config = [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'web_name',  // filled by install script
    'controllerNamespace' => 'frontend\controllers',
	'defaultRoute' => 'page/home',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\UserRecord',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
	        'enablePrettyUrl' => true,
	        'showScriptName' => false,
	        'rules' => $urlRules
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
	        'translations' => [
		        'front*' => [
			        'class' => 'yii\i18n\PhpMessageSource',
			        'basePath' => '@frontend/messages',
			        'fileMap' => [
				        'front' => 'front.php',
				        'front/error' => 'error.php',
			        ],
		        ],
		        'app*' => [
			        'class' => 'yii\i18n\PhpMessageSource',
			        'basePath' => '@common/messages',
			        'fileMap' => [
				        'app' => 'app.php',
				        'app/error' => 'error.php',
			        ],
		        ],
	        ],
        ],
        'pdf' => [
	        'class' => Pdf::className(),
	        'cssFile' => '@webroot/basic-assets/css/pdf.css'
        ],
	    'assetManager' => [
		    'bundles' => [
			    'dosamigos\google\maps\MapAsset' => [
				    'options' => [
					    'key' => 'AIzaSyA-1jkGfN_9u8onoQytDufbAzK5eS2FYrY'
				    ]
			    ]
		    ]
	    ]
    ],
    'params' => $params,
];

if (!YII_ENV_TEST) {
	// configuration adjustments for 'dev' environment
	$config['modules']['install']['class'] = 'frontend\modules\install\Module';
}

return $config;