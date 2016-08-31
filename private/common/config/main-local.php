<?php
return [
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=localhost;dbname=database',  // filled by install console script
			'username' => 'user',  // filled by install console script
			'password' => 'pswd',  // filled by install console script
			'charset' => 'utf8',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			'useFileTransport' => true, // false in production mode
		],
	],
];
