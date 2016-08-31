<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 31.8.2016
 * Time: 13:19
 */

namespace frontend\modules\install\models;


use Exception;
use yii\base\Model;

class InstallForm extends Model
{
	/**
	 * Updates config file
	 *
	 * @param $configFile
	 * @param $items
	 * @param bool $string
	 *
	 * @throws Exception
	 */
	protected function setConfig($configFile, $items, $string = true) {
		$configFileContent     = file_get_contents( $configFile );
		foreach ( $items as $item ) {
			$configFileContent = preg_replace('/' . $item . '\'\s*=>\s*' . ($string === true ? '\'[\w\s\-:_\.,;@=]*\'/u' : '[\d]*/'), $item . '\' => ' . ($string === true ? '\'' . $this->$item .'\'' : $this->$item), $configFileContent);
		}
		if ( !file_put_contents( $configFile, $configFileContent ) ) {
			throw new Exception( 'Updated configuration file not saved!' );
		}
	}
}