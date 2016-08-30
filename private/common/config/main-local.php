<?php
return [
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=localhost;dbname=database',
			'username' => 'user',
			'password' => 'pswd',
			'charset' => 'utf8',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			'useFileTransport' => true, // false in production mode
		],
	],
];
