<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 10:00
 */

namespace backend\models;


use backend\utilities\ContentForm;
use common\models\FileRecord;
use common\models\Image;
use common\models\LanguageRecord;
use common\models\Page;
use Yii;

class PageForm extends ContentForm
{
	/**
	 * PageForm constructor
	 * @param integer|null $item_id
	 */
	public function __construct( $item_id = null ) {
		parent::__construct();
		if ($item_id) {
			/** @var $page Page */
			$page = Page::findOne($item_id);
			$this->item_id = $page->id;
			$this->language_id = $page->language_id;
			if ($page->image)
				$this->imageFilename = $page->image->filename;
			$this->perex = $page->perex;
			$this->title = $page->title;
			$this->description = $page->description;
			if ($page->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			if ($page->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
			$this->tagValues = $page->tagValues;
		} else {
			$session = Yii::$app->session;
			if (!$session['language_id'])
				$session['language_id'] = LanguageRecord::getMainLanguageId();
			$this->language_id = $session['language_id'];

			$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->boxes[] = self::PROPERTY_PUBLIC;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['title','required', 'on' => ['create', 'update']],
			[['title', 'imageFilename'], 'string', 'max' => 255],
			[['perex', 'description', 'item_id', 'language_id', 'boxes', 'tagValues'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'imageFilename' => Yii::t('back', 'Image for perex'),
			'perex' => Yii::t('back', 'Perex'),
			'title' => Yii::t('back', 'Title'),
			'description' => Yii::t('back', 'Content'),
			'item_id' => Yii::t('back', 'Actual Item'),
			'language_id' => Yii::t('back', 'Language'),
			'boxes' => Yii::t('back', 'Properties'),
			'tagValues' => Yii::t('back', 'Tags')
		];
	}

	/**
	 * Save Page model
	 * @param bool $insert
	 */
	public function savePage($insert = true) {
		$page = $insert === true ? new Page() : Page::findOne($this->item_id);
		$imageId = null;
		if (!isset(Yii::$app->request->post('PageForm')['imageFilename'])) $this->imageFilename = null;
		if ($this->imageFilename) {
			$imageId = Image::find()->andWhere( [ 'filename' => $this->getImageName() ] )->scalar();
			if (!$imageId) {
				$imageId = FileRecord::saveFileFromFilename($this->getImageName());
			}
		}
		$this->image_id = $imageId;
		$page->attributes = $this->toArray();
		$page->active   = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$page->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
		$page->tagValues = $this->tagValues;
		if ($page->save( false )) {
			$this->item_id = $page->id;
		}
	}

	/**
	 * Deletes Page
	 * @throws \Exception
	 */
	public function deletePage() {
		/** @var $page Page */
		if ($page = Page::findOne($this->item_id)) {
			/** @noinspection PhpUndefinedMethodInspection */
			$page->removeAllTagValues();
			$page->delete();
		}
	}

	/**
	 * Returns short image name
	 * @return string
	 */
	public function getImageName() {
		if (strstr($this->imageFilename, Yii::getAlias('@web') . '/' . Yii::$app->params['imageUploadDir'])) {
			$startPosition = strlen(Yii::getAlias('@web') . '/' . Yii::$app->params['imageUploadDir']);
			$filename = substr($this->imageFilename, $startPosition);
		} else {
			$filename = $this->imageFilename;
		}
		return $filename;
	}
}