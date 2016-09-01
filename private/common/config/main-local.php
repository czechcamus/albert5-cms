<?php
return [
	'components' => [
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=localhost;dbname=database_name',  // filled by install console script
			'username' => 'database_user',  // filled by install console script
			'password' => 'user_password',  // filled by install console script
			'charset' => 'utf8',
		],
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'viewPath' => '@common/mail',
			'useFileTransport' => true, // false in production mode
		],
	],
];
