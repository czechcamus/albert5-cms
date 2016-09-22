<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 29.10.2015
 * Time: 8:33
 */

namespace backend\utilities;


use iutbay\yii2kcfinder\KCFinderInputWidget;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;

class KCFinder extends KCFinderInputWidget
{
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();

		if (!isset($this->kcfOptions['uploadURL']))
		{
			$this->kcfOptions['uploadURL'] = Yii::getAlias('@web').'/upload';
		}
		$changeChars = array_merge(Inflector::$transliteration, Yii::$app->params['changeChars']);
		$this->kcfOptions['filenameChangeChars'] = $changeChars;
		$this->kcfOptions['dirnameChangeChars'] = $changeChars;
		$this->kcfOptions = array_merge($this->kcfOptions, Yii::$app->params['kcfDefaultOptions']);
		Yii::$app->session['KCFINDER'] = $this->kcfOptions;

		$this->clientOptions['browseOptions'] = $this->kcfBrowseOptions;
		$this->clientOptions['uploadURL'] = $this->kcfOptions['uploadURL'];
		$this->clientOptions['multiple'] = $this->multiple;
		$this->clientOptions['inputName'] = $this->getInputName();
		$this->clientOptions['thumbsDir'] = $this->kcfOptions['thumbsDir'];
		$this->clientOptions['thumbsSelector'] = '#'.$this->getThumbsId();
		$this->clientOptions['thumbTemplate'] = $this->thumbTemplate;

		$this->buttonOptions['id'] = $this->getButtonId();

		Html::addCssClass($this->options, 'form-control');
	}
}