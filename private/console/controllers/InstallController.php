<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 29.8.2016
 * Time: 8:26
 */

namespace console\controllers;


use yii\base\Exception;
use yii\console\Controller;

class InstallController extends Controller {
	public $defaultAction = 'make';

	public function actionMake() {
		// create necessary directories
		$this->createDirectories( str_replace( '\\', '/', __DIR__ ) . '/../../../web', [
				'assets',
				'admin/assets',
				'admin/upload',
				'admin/upload/files',
				'admin/upload/images',
			]
		);

		// set database connection credentials
		$pattern              = '[A-Za-z0-9\-_]*';
		$dbname               = $this->prompt( 'Name of database: ', [
			'required' => true,
			'pattern'  => '/^' . $pattern . '$/'
		] );
		$file = \Yii::getAlias( '@common' ) . '/config/main-local.php';
		if (!$this->updateConfigFile($file, 'dbname=', $pattern, 'dbname=', $dbname)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/dev/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'dbname=', $pattern, 'dbname=', $dbname)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'dbname=', $pattern, 'dbname=', $dbname)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}

		$username               = $this->prompt( 'Name of database user: ', [
			'required' => true,
			'pattern'  => '/^' . $pattern . '$/'
		] );
		$file = \Yii::getAlias( '@common' ) . '/config/main-local.php';
		if (!$this->updateConfigFile($file, 'username\'\s*=>\s*\'', $pattern, 'username\' => \'', $username)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/dev/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'username\'\s*=>\s*\'', $pattern, 'username\' => \'', $username)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'username\'\s*=>\s*\'', $pattern, 'username\' => \'', $username)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}

		$password               = $this->prompt( 'Password of database user: ', [
			'required' => true,
			'pattern'  => '/^' . $pattern . '$/'
		] );
		$file = \Yii::getAlias( '@common' ) . '/config/main-local.php';
		if (!$this->updateConfigFile($file, 'password\'\s*=>\s*\'', $pattern, 'password\' => \'', $password)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/dev/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'password\'\s*=>\s*\'', $pattern, 'password\' => \'', $password)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'password\'\s*=>\s*\'', $pattern, 'password\' => \'', $password)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
	}

	/**
	 * Creates necessary directories
	 * @param $root
	 * @param $directories
	 */
	private function createDirectories( $root, $directories ) {
		foreach ( $directories as $directory ) {
			echo "           ...create dir " . $root . '/' . $directory . "\n";
			@mkdir( $root . '/' . $directory );
		}
	}

	/**
	 * Updates config file
	 * @param $file
	 * @param $patternPrefix
	 * @param $pattern
	 * @param $valuePrefix
	 * @param $value
	 *
	 * @return int
	 */
	private function updateConfigFile( $file, $patternPrefix, $pattern, $valuePrefix, $value ) {
		$content = preg_replace('/' . $patternPrefix . $pattern . '\'/', $valuePrefix . $value . '\'', file_get_contents($file));
		return file_put_contents($file, $content);
	}
}