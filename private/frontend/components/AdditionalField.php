<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 23.9.2015
 * Time: 10:42
 */

namespace frontend\components;


use common\models\PageFieldRecord;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;

/**
 * Class AdditionalFiled displays additional field of given page
 * @property $contentId integer is ID of page
 * @property $addFieldId integer is ID of additional field
 * @property $viewName string name of view file
 * @package frontend\components
 */
class AdditionalField extends Widget
{
	/** @var  int */
	public $pageId;

	/** @var  int */
	public $addFieldId;

	/** @var string  */
	public $viewName = '';

	private $_field;

	public function init() {
		parent::init();
		if ($this->pageId) {
			if ($this->addFieldId) {
				$this->_field = PageFieldRecord::findOne(['page_id' => $this->pageId, 'additional_field_id' => $this->addFieldId]);
			} else {
				throw new InvalidParamException( Yii::t( 'front', 'No required parameter given') . ' - addFieldId');
			}
		} else {
			throw new InvalidParamException( Yii::t( 'front', 'No required parameter given') . ' - contentId');
		}
	}

	public function run() {
		if ($this->viewName) {
			return $this->render($this->viewName, [
				'field' => $this->_field
			]);
		} else {
			return $this->_field->additionalField->label . ': ' . $this->_field->content;
		}
	}
}