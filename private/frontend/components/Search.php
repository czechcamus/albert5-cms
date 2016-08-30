<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 13.10.2015
 * Time: 21:10
 */

namespace frontend\components;


use frontend\models\SearchContent;
use yii\base\Widget;

/**
 * Class Search for rendering search form
 * @package frontend\components
 */
class Search extends Widget
{
	public function init() {
		parent::init();
	}

	public function run() {
		$model = new SearchContent();
		return $this->render('searchForm', compact('model'));
	}
}