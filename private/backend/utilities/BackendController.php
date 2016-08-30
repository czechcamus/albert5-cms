<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 31.10.2015
 * Time: 13:13
 */

namespace backend\utilities;


use Yii;
use yii\helpers\Inflector;
use yii\web\Controller;

class BackendController extends Controller
{
	public function init() {
		parent::init();
		// KCFinder settings
		$changeChars = array_merge(Inflector::$transliteration, Yii::$app->params['changeChars']);
		$kcfOptions = [
			'uploadURL' => Yii::getAlias('@web').'/upload',
			'filenameChangeChars' => $changeChars,
			'dirnameChangeChars' => $changeChars
		];
		Yii::$app->session['KCFINDER'] = array_merge(Yii::$app->params['kcfDefaultOptions'], $kcfOptions);
	}
}