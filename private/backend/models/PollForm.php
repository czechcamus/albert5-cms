<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.9.2015
 * Time: 21:25
 */

namespace backend\models;


use common\models\LanguageRecord;
use common\models\PollRecord;
use common\models\PollAnswerRecord;
use Yii;
use yii\base\Model;

class PollForm extends Model
{
	/** @var integer actual page id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var string question od page */
	public $question;
	/** @var string end date of poll */
	public $end_date;
	/** @var array boxes of properties */
	public $boxes;
	/** @var array of answers options */
	public $answers = [];
	/** @var boolean if are editable answers */
	public $isAnswersEditable;

	const PROPERTY_ACTIVE = 1;
	const PROPERTY_MAIN = 2;

	/**
	 * @param integer|null $item_id
	 */
	public function __construct( $item_id = null ) {
		parent::__construct();
		if ($item_id) {
			/** @var $poll PollRecord */
			$poll = PollRecord::findOne($item_id);
			$this->item_id = $poll->id;
			$this->language_id = $poll->language_id;
			$this->question = $poll->question;
			$this->end_date = $poll->end_date ? Yii::$app->formatter->asDate($poll->end_date, 'dd.MM.y') : null;
			if ($poll->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			if ($poll->main)
				$this->boxes[] = self::PROPERTY_MAIN;
			foreach ( $poll->answers as $pollAnswer  ) {
				$this->answers[] = $pollAnswer->answer;
			}
			$voices = PollAnswerRecord::find()->where(['poll_id' => $poll->id])->andWhere('voices>0')->count();
			$this->isAnswersEditable = !$voices;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];
			$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->answers[1] = $this->answers[0] = '';
			$this->isAnswersEditable = true;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['question','required', 'on' => ['create', 'update']],
			[['question'], 'string', 'max' => 255],
			[['end_date'], 'default', 'value' => null],
			[['end_date'], 'date', 'format' => 'dd.MM.y'],
			[['item_id', 'language_id', 'boxes', 'answers'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'question' => Yii::t('back', 'Poll question'),
			'boxes' => Yii::t('back', 'Properties'),
			'end_date' => Yii::t('back', 'End date'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'answers' => Yii::t('back', 'Answers')
		];
	}

	/**
	 * Save PollRecord model
	 * @param bool $insert
	 */
	public function savePoll($insert = true) {
		$poll = $insert === true ? new PollRecord : PollRecord::findOne($this->item_id);
		$poll->attributes = $this->toArray();
		if ($this->end_date)
			$poll->end_date = Yii::$app->formatter->asDate($this->end_date, 'y-MM-dd');
		$poll->active   = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$poll->main = (is_array($this->boxes) && in_array(self::PROPERTY_MAIN, $this->boxes)) ? 1 : 0;
		if ($this->item_id && $this->isAnswersEditable) {
			PollAnswerRecord::deleteAll(['poll_id' => $this->item_id]);
		}
		$poll->save(false);
		if (is_array($this->answers) && $this->isAnswersEditable) {
			foreach ( $this->answers as $answer ) {
				$pollAnswer = new PollAnswerRecord;
				$pollAnswer->poll_id = $poll->id;
				$pollAnswer->answer = $answer;
				$pollAnswer->save();
			}
		}
	}

	/**
	 * Deletes Poll
	 * @throws \Exception
	 */
	public function deletePoll() {
		/** @var $poll PollRecord */
		if ($poll = PollRecord::findOne($this->item_id))
			$poll->delete();
	}

	public function renderAnswerFields() {
		$fields = '';
		for ($i = 2; $i < count($this->answers); $i++ ) {
			$fields .= '<div class="input-field-row"><label class="control-label col-sm-2">';
			if ($this->isAnswersEditable) {
				$fields .= '<a href="#" class="remove_field"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> ';
			}
			$fields .= ( $i + 1 ) . '.</label><div class="col-sm-9"><input type="text" name="PollForm[answers][' . $i . ']" class="form-control answer-field" value="' . $this->answers[ $i ] . '"'
			           . ($this->isAnswersEditable ? '' : ' disabled="disabled"') .' /></div></div>';
		}
		return $fields;
	}
}