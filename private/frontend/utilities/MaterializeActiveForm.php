<?php

namespace frontend\utilities;

use yii\widgets\ActiveForm;

/**
 * Class ActiveForm extends yii\widgets\ActiveFrom for Materialize CSS
 * @package frontend\utilities\materialize
 */
class MaterializeActiveForm extends ActiveForm
{
	/**
	 * @inheritdoc
	 */
	public $fieldClass = 'frontend\utilities\MaterializeActiveField';
}