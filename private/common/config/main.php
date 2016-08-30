<?php
use kartik\datecontrol\Module;

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
	    'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest']
        ],
	    'formatter' => [
		    'defaultTimeZone' => 'Europe/Prague',
		    'timeZone' => 'Europe/Prague'
	    ]
    ],
	'modules' => [
		'datecontrol' => [
			'class' => 'kartik\datecontrol\Module',
			'displaySettings' => [
				Module::FORMAT_DATE => 'dd.MM.yyyy',
				Module::FORMAT_TIME => 'HH:mm:ss',
				Module::FORMAT_DATETIME => 'dd.MM.yyyy HH:mm:ss'
			],
			'saveSettings' => [
				Module::FORMAT_DATE => 'php:Y-m-d',
				Module::FORMAT_TIME => 'php:H:i:s',
				Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s'
			],
			'widgetSettings' => [
				Module::FORMAT_DATE => [
					'class' => 'yii\jui\DatePicker',
					'options' => [
						'options' => [
							'class' => 'form-control'
						]
					]
				]
			]
		]
	]
];
