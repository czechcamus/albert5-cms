<?php

namespace common\models;

use common\utilities\UniqueMainProperty;
use common\utilities\StatusQuery;
use common\utilities\UpdateLayoutId;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "layout".
 *
 * @property integer $id
 * @property string $title
 * @property string $filename
 * @property integer $main
 * @property integer $active
 * @property integer $content
 *
 * @property ContentRecord[] $contents
 * @property MenuItemRecord[] $menuItems
 */
class LayoutRecord extends ActiveRecord
{
	/** @var array boxes of properties */
	public $boxes = [];

	const PROPERTY_MAIN = 1;
	const PROPERTY_ACTIVE = 2;

	const CONTENT_PAGE = 1;
	const CONTENT_CATEGORY = 2;
	const CONTENT_ARTICLE = 3;
	const CONTENT_NEWSLETTER = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'layout';
    }

	/**
	 * @return array configuration of behaviors.
	 */
	public function behaviors()
	{
		return [
			'main' => [
				'class' => UniqueMainProperty::className(),
				'filterAttributes' => [
					[
						'attribute' => 'content',
						'operator' => '='
					]
				]
			],
			'updateLayoutId' => UpdateLayoutId::className()
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'filename'], 'required'],
            [['main', 'active', 'content'], 'integer'],
	        ['boxes', 'safe'],
            [['title'], 'string', 'max' => 255],
            [['filename'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'filename' => Yii::t('app', 'Filename'),
            'main' => Yii::t('app', 'Main'),
            'active' => Yii::t('app', 'Active'),
            'content' => Yii::t('app', 'Content'),
            'boxes' => Yii::t('app', 'Properties')
        ];
    }

	/**
	 * @inheritdoc
	 * @return StatusQuery
	 */
	public static function find()
	{
		return new StatusQuery(get_called_class());
	}

	/**
	 * @param bool $empty indicates compare value
	 * @return bool
	 */
	public static function existsMoreLayoutRecords($empty = true)
	{
		$query = 'SELECT COUNT(*) FROM layout';
		$count = Yii::$app->db->createCommand($query)->queryScalar();
		$compareValue = $empty ? '0' : '1';
		return $count > $compareValue ? true : false;
	}

	/**
	 * Returns Id of main layout
	 * @param int $content indicates content specific layout
	 * @return bool|null|string
	 */
	public static function getMainLayoutId($content = self::CONTENT_PAGE) {
		return Yii::$app->db->createCommand('SELECT id FROM layout WHERE main = 1 AND content = :content', [':content' => $content])->queryScalar();

	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(ContentRecord::className(), ['layout_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItemRecord::className(), ['layout_id' => 'id']);
    }

	/**
	 * Initializes boxes property
	 * @param bool $insert
	 */
	public function initBoxes($insert = true) {
		if ($insert) {
			if ($this->existsMoreLayoutRecords() === false) {
				$this->boxes[] = self::PROPERTY_MAIN;
			}
			$this->boxes[] = self::PROPERTY_ACTIVE;
		} else {
			if ($this->main)
				$this->boxes[] = self::PROPERTY_MAIN;
			if ($this->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
		}
	}

	/**
	 * Sets main, active and public properties from boxes array
	 */
	public function setBoxesProperties() {
		$this->main = (is_array($this->boxes) && in_array(self::PROPERTY_MAIN, $this->boxes)) ? 1 : 0;
		$this->active = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
	}

	/**
	 * Returns content options for dropdown
	 * @return array
	 */
	public function getContentOptions() {
		return [
			self::CONTENT_PAGE => Yii::t('app', 'Page'),
			self::CONTENT_CATEGORY => Yii::t('app', 'Category'),
			self::CONTENT_ARTICLE => Yii::t('app', 'Article'),
			self::CONTENT_NEWSLETTER => Yii::t('app', 'Newsletter')
		];
	}

	/**
	 * Returns content option text
	 * @return string
	 */
	public function getContentOptionText() {
		$optionsArray = $this->getContentOptions();
		return $optionsArray[$this->content];
	}
}
