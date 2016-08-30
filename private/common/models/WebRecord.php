<?php

namespace common\models;

use common\utilities\RelationsDelete;
use common\utilities\UniqueMainProperty;
use common\utilities\StatusQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "web".
 *
 * @property integer $id
 * @property string $title
 * @property string $weburl
 * @property integer $active
 * @property integer $main
 * @property integer $public
 * @property string $theme
 *
 * @property MenuRecord[] $menus
 */
class WebRecord extends ActiveRecord
{
	/** @var array boxes of properties */
	public $boxes = [];

	const PROPERTY_MAIN = 1;
	const PROPERTY_ACTIVE = 2;
	const PROPERTY_PUBLIC = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'web';
    }

    /**
	 * @return array configuration of behaviors.
	 */
	public function behaviors()
	{
		return [
			'main' => UniqueMainProperty::className(),
			'relationsDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['menus']
			]

		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme'], 'filter', 'filter' => 'trim'],
            [['title', 'weburl', 'theme'], 'required'],
            [['active', 'main', 'public'], 'integer'],
	        ['boxes', 'safe'],
            [['title', 'weburl', 'theme'], 'string', 'min' => 3, 'max' => 255]
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
    public static function existsMoreWebRecords($empty = true)
    {
        $count = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM web')->queryScalar();
        $compareValue = $empty ? '0' : '1';
        return $count > $compareValue ? true : false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'weburl' => Yii::t('app', 'Web ID'),
            'active' => Yii::t('app', 'Active'),
            'main' => Yii::t('app', 'Main'),
            'public' => Yii::t('app', 'Public'),
            'theme' => Yii::t('app', 'Theme'),
            'boxes' => Yii::t('app', 'Properties')
        ];
    }

    /**
     * @return array navbar items
     */
    public static function getNavBarItems()
    {
        //$webItems = self::find()->orderBy('main DESC')->all();
        $webItems = self::find()->orderBy('main DESC')->activeStatus()->all();
        $navBarItems = [];
        foreach ($webItems as $webItem)
        {
            /** @noinspection PhpUndefinedFieldInspection */
            $navBarItems[] = ['label' => $webItem->title, 'url' => '../../' . ($webItem->main ? '' : $webItem->weburl)];
        }

        return $navBarItems;
    }

	/**
	 * Gets web options for dropdown
	 * @return array
	 */
	public static function getWebOptions() {
		return ArrayHelper::map(self::find()->orderBy('main DESC')->all(), 'id', 'title');
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(MenuRecord::className(), ['web_id' => 'id']);
    }

	/**
	 * Gets ID of main web
	 * @return bool|null|string
	 */
    public static function getMainWebId() {
	    return Yii::$app->db->createCommand('SELECT id FROM web WHERE main = 1')->queryScalar();
    }

	/**
	 * Initializes boxes property
	 * @param bool $insert
	 */
	public function initBoxes($insert = true) {
		if ($insert) {
            if (self::existsMoreWebRecords() === false) {
                $this->boxes[] = self::PROPERTY_MAIN;
            }
			$this->boxes[] = self::PROPERTY_ACTIVE;
			$this->boxes[] = self::PROPERTY_PUBLIC;
		} else {
			if ($this->main)
				$this->boxes[] = self::PROPERTY_MAIN;
			if ($this->active)
				$this->boxes[] = self::PROPERTY_ACTIVE;
			if ($this->public)
				$this->boxes[] = self::PROPERTY_PUBLIC;
		}
	}

	/**
	 * Sets main, active and public properties from boxes array
	 */
	public function setBoxesProperties() {
		$this->main = (is_array($this->boxes) && in_array(self::PROPERTY_MAIN, $this->boxes)) ? 1 : 0;
		$this->active = (is_array($this->boxes) && in_array(self::PROPERTY_ACTIVE, $this->boxes)) ? 1 : 0;
		$this->public = (is_array($this->boxes) && in_array(self::PROPERTY_PUBLIC, $this->boxes)) ? 1 : 0;
	}
}
