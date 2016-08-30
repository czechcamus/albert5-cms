<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 10:00
 */

namespace backend\models;


use common\models\LanguageRecord;
use common\models\LayoutRecord;
use common\models\Newsletter;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class NewsletterForm extends Model
{
	/** @var integer actual newsletter id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var string title od newsletter */
	public $title;
	/** @var string description of newsletter */
	public $description;
	/** @var integer layout id */
	public $layout_id;
	/** @var array boxes of properties */
	public $boxes;

	const PROPERTY_ACTIVE = 1;

	/**
	 * NewsletterForm constructor
	 * @param integer|null $item_id
	 */
	public function __construct( $item_id = null ) {
		parent::__construct();
		if ($item_id) {
			/** @var $newsletter Newsletter */
			$newsletter = Newsletter::findOne($item_id);
			$this->item_id = $newsletter->id;
			$this->language_id = $newsletter->language_id;
			$this->title = $newsletter->title;
			$this->description = $newsletter->description;
			if ($newsletter->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->layout_id = $newsletter->layout_id;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];

			$this->boxes[] = self::PROPERTY_ACTIVE;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['title','required', 'on' => ['create', 'update']],
			[['title'], 'string', 'max' => 255],
			[['description', 'item_id', 'language_id', 'layout_id', 'boxes'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('back', 'Title'),
			'description' => Yii::t('back', 'Content'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'layout_id' => Yii::t('back', 'Layout'),
			'boxes' => Yii::t('back', 'Properties')
		];
	}

	/**
	 * Save Newsletter model
	 * @param bool $insert
	 */
	public function saveNewsletter($insert = true) {
		$newsletter = $insert === true ? new Newsletter() : Newsletter::findOne($this->item_id);
		$newsletter->attributes = $this->toArray();
		$newsletter->active   = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$newsletter->save(false);
	}

	/**
	 * Deletes Newsletter
	 * @throws \Exception
	 */
	public function deleteNewsletter() {
		/** @var $newsletter Newsletter */
		if ($newsletter = Newsletter::findOne($this->item_id))
			$newsletter->delete();
	}

	/**
	 * Returns layout options for dropdown
	 * @return array
	 */
	public function getLayoutListOptions() {
		return ArrayHelper::map(LayoutRecord::find()->where([
			'content' => LayoutRecord::CONTENT_NEWSLETTER
		])->activeStatus()->orderBy([
			'main' => SORT_DESC,
			'title' => SORT_ASC
		])->all(), 'id', 'title');
	}
}