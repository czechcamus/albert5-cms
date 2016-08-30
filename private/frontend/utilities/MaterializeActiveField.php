<?php

namespace frontend\utilities;

use yii\widgets\ActiveField;

/**
 * Class MaterializeActiveField for use in MaterializeActiveForm
 * @package frontend\utilities
 */
class MaterializeActiveField extends ActiveField
{
	/**
	 * @var null $options for Materialize CSS fields
	 */
	public $options = ['class' => 'input-field col s12'];

	/**
	 * @var string $template for Materialize CSS fields
	 */
	public $template = "{input}\n{label}\n{hint}\n{error}";

	/**
	 * @var array $inputOptions dafaults to nothing needed
	 */
	public $inputOptions = [];

	/**
	 * @var array $labelOptions dafaults to nothing needed
	 */
	public $labelOptions = [];
}