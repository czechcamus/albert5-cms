<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 13.6.2015
 * Time: 15:19
 */

namespace backend\utilities;


use iutbay\yii2kcfinder\KCFinderAsset;
use yii\helpers\ArrayHelper;

class CKEditor extends \dosamigos\ckeditor\CKEditor
{
	public $enableKCFinder = true;

	/**
	 * Registers CKEditor plugin
	 */
	protected function registerPlugin() {
		if ($this->enableKCFinder) {
			$this->registerKCFinder();
		}

		parent::registerPlugin();
	}

	/**
	 * Registers KCFinder
	 */
	protected function registerKCFinder() {
		$register = KCFinderAsset::register($this->view);
		$kcfinderUrl = $register->baseUrl;

		$browseOptions = [
			'filebrowserBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=files&lang=cs',
			'filebrowserImageBrowseUrl' => $kcfinderUrl . '/browse.php?opener=ckeditor&type=images&lang=cs',
			'filebrowserUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=files&lang=cs',
			'filebrowserImageUploadUrl' => $kcfinderUrl . '/upload.php?opener=ckeditor&type=images&lang=cs',
		];

		$this->clientOptions = ArrayHelper::merge($browseOptions, $this->clientOptions);
	}
}