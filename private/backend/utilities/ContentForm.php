<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 21.3.2017
 * Time: 10:32
 */

namespace backend\utilities;


use common\models\ContentFieldRecord;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\Html;

class ContentForm extends Model
{
	/** @var integer actual content id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var integer image id */
	public $image_id;
	/** @var string image filename */
	public $imageFilename;
	/** @var string perex */
	public $perex;
	/** @var string title od content */
	public $title;
	/** @var string description of content */
	public $description;
	/** @var array boxes of properties */
	public $boxes;
	/** @var string tag values */
	public $tagValues;

	const PROPERTY_ACTIVE = 1;
	const PROPERTY_PUBLIC = 2;

	/**
	 * Renders additional fields for actual content
	 */
	public function renderAdditionalFields() {
		$output = '';
		if ($this->item_id) {
			$contentFields = ContentFieldRecord::findAll(['content_id' => $this->item_id]);
			foreach ( $contentFields as $contentField ) {
				$output .= '<div class="form-inline"><div class="form-group">';
				$output .= '<label for="AdditionalFieldId[' . $contentField->additional_field_id . ']">' . $contentField->additionalField->label .'</label><br />';
				$output .= Html::textInput('AdditionalFieldId[' . $contentField->additional_field_id . ']', $contentField->content, ['class' => 'form-control']);
				$output .= ' ' . Html::a('<span class="glyphicon glyphicon-remove"></span>', '#!', ['class' => 'form-control btn btn-danger remove-field-btn']);
				$output .= '</div></div>';
			}
		}
		echo $output;
	}

	/**
	 * Returns available fields for adding to content form
	 * @return array
	 */
	public function getAvailableFields() {
		$usedAdditionalFieldsArray = [];
		$allAdditionalFieldsArray = (new Query())->select('id')->from('additional_field')->where(['language_id' => $this->language_id])->column();
		if ($this->item_id) {
			$usedAdditionalFieldsArray = (new Query())->select('additional_field_id')->from('content_field')->where(['content_id' => $this->item_id])->column();
		}
		return array_diff($allAdditionalFieldsArray, $usedAdditionalFieldsArray);
	}
}