<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 21.3.2017
 * Time: 11:42
 */

namespace backend\utilities;


use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;

class ContentController extends BackendController
{
	/**
	 * @inheritdoc$
	 */
	public function behaviors()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'roles' => ['user'],
						'allow' => true
					]
				]
			],
			'synchronize'   => [
				'class' => SynchronizeFiles::className(),
				'only'  => [ 'create', 'copy', 'update' ]
			]
		];
	}

	/**
	 * Adds selection for additional fields to page form
	 * @return string
	 */
	public function actionShowAdditionalFieldForm() {
		$availableFieldsArray = Yii::$app->request->get('available_fields');
		$output = '';
		$output .= '<div class="form-group">';
		$output .= '<label for="additional-field-record_id">' . Yii::t('back', 'Select additional field to add') . '</label>';
		$output .= '<select name="AdditionalFieldRecord[id]" id="additional-field-record_id" class="form-control">';
		foreach ( $availableFieldsArray as $item ) {
			$label = (new Query())->select('label')->from('additional_field')->where(['id' => $item])->scalar();
			$output .= '<option value="' . $item . '">' . $label . '</option>';
		}
		$output .= '</select></div>';
		$output .= Html::a(Yii::t('back','add'), '#!', [
			'id' => 'save-field-btn',
			'class' => 'btn btn-success'
		]);
		$output .= ' ' . Html::a(Yii::t('back','cancel'), '#!', [
				'id' => 'cancel-field-btn',
				'class' => 'btn btn-default'
			]);
		return $output;
	}

	/**
	 * Displays additional field
	 * @return string
	 */
	public function actionShowAdditionalField() {
		$output = '';
		$additionalFieldId = Yii::$app->request->get('additional_field_id');
		$additionalFieldLabel = (new Query())->select('label')->from('additional_field')->where(['id' => $additionalFieldId])->scalar();
		$output .= '<div class="form-inline"><div class="form-group">';
		$output .= '<label for="AdditionalFieldId[' . $additionalFieldId . ']">' . $additionalFieldLabel .'</label><br />';
		$output .= Html::textInput('AdditionalFieldId[' . $additionalFieldId . ']', null, ['class' => 'form-control']);
		$output .= ' ' . Html::a('<span class="glyphicon glyphicon-remove"></span>', '#!', [
				'class' => 'form-control btn btn-danger remove-field-btn',
				'data-field-id' => $additionalFieldId
			]);
		$output .= '</div></div>';
		return $output;
	}
}