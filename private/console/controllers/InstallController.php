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

		// set CKEditor
		$sourceDir = \Yii::getAlias('@frontend') . '/modules/install/data/ckeditor';
		$destinationDir = \Yii::getAlias('@vendorApp') . '/2amigos/yii2-ckeditor-widget/src/assets/ckeditor';
		echo "           ...copy " . $sourceDir . "/config.js " . $destinationDir . "/config.js\n";
		@copy($sourceDir . '/config.js', $destinationDir . '/config.js');
		$this->copyCKEditorPlugins($sourceDir, $destinationDir, ['article', 'gallery', 'poll', 'sound', 'youtube']);

		// set KCFinder
		$sourceDir = \Yii::getAlias('@frontend') . '/modules/install/data/kcfinder';
		$destinationDir = \Yii::getAlias('@vendorApp') . '/sunhater/kcfinder/core';
		echo "           ...copy " . $sourceDir . "/autoload.php " . $destinationDir . "/autoload.php\n";
		@copy($sourceDir . '/autoload.php', $destinationDir . '/autoload.php');

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
		$file = \Yii::getAlias( '@environments' ) . '/dev/private/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'dbname=', $pattern, 'dbname=', $dbname)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/private/common/config/main-local.php';
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
		$file = \Yii::getAlias( '@environments' ) . '/dev/private/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'username\'\s*=>\s*\'', $pattern, 'username\' => \'', $username)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/private/common/config/main-local.php';
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
		$file = \Yii::getAlias( '@environments' ) . '/dev/private/common/config/main-local.php';
		if (!$this->updateConfigFile($file, 'password\'\s*=>\s*\'', $pattern, 'password\' => \'', $password)) {
			throw new Exception( 'Update of configuration file failed - ' . $file );
		}
		$file = \Yii::getAlias( '@environments' ) . '/prod/private/common/config/main-local.php';
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

	private function copyCKEditorPlugins( $sourceDir, $destinationDir, $plugins ) {
		foreach ( $plugins as $plugin ) {
			if (!is_dir($destinationDir . '/plugins/' . $plugin)) {
				echo "           ...create dir " . $destinationDir . '/plugins/' . $plugin . "\n";
				@mkdir($destinationDir . '/plugins/' . $plugin);
				@mkdir($destinationDir . '/plugins/' . $plugin . '/dialogs');
				@mkdir($destinationDir . '/plugins/' . $plugin . '/icons');
			}
			echo "           ...copy " . $sourceDir . '/plugins/' . $plugin . "/plugin.js " . $destinationDir . '/plugins/' . $plugin . "/plugin.js\n";
			@copy($sourceDir . '/plugins/' . $plugin . '/plugin.js', $destinationDir . '/plugins/' . $plugin . '/plugin.js');
			echo "           ...copy " . $sourceDir . '/plugins/' . $plugin . "/dialogs/" . $plugin . ".js " . $destinationDir . '/plugins/' . $plugin . "/dialogs/" . $plugin . ".js\n";
			@copy($sourceDir . '/plugins/' . $plugin . '/dialogs/' . $plugin . '.js', $destinationDir . '/plugins/' . $plugin . '/dialogs/' . $plugin . '.js');
			echo "           ...copy " . $sourceDir . '/plugins/' . $plugin . "/icons/" . $plugin . ".js " . $destinationDir . '/plugins/' . $plugin . "/icons/" . $plugin . ".js\n";
			@copy($sourceDir . '/plugins/' . $plugin . '/icons/' . $plugin . '.png', $destinationDir . '/plugins/' . $plugin . '/icons/' . $plugin . '.png');
		}
	}
}