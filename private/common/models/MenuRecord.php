<?php

namespace common\models;

use common\utilities\RelationsDelete;
use common\utilities\StatusQuery;
use common\utilities\UniqueMainProperty;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $title
 * @property string $text_id
 * @property integer $web_id
 * @property integer $active
 * @property integer $main
 * @property integer $public
 *
 * @property MenuItemRecord[] $menuItems
 * @property WebRecord $web
 */
class MenuRecord extends ActiveRecord
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
        return 'menu';
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
                        'attribute' => 'web_id',
                        'operator' => '='
                    ]
                ]
            ],
            'relationsDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['menuItems']
			]

		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text_id'], 'required'],
            [['web_id', 'active', 'main', 'public'], 'integer'],
            ['boxes', 'safe'],
            [['title', 'text_id'], 'string', 'max' => 255]
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
            'text_id' => Yii::t('app', 'Text ID'),
            'web_id' => Yii::t('app', 'Web ID'),
            'active' => Yii::t('app', 'Active'),
            'main' => Yii::t('app', 'Main'),
            'public' => Yii::t('app', 'Public'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItemRecord::className(), ['menu_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWeb()
    {
        return $this->hasOne(WebRecord::className(), ['id' => 'web_id']);
    }

    /**
     * @return array web list for dropdown
     */
    public function getWebOptions()
    {
        return ArrayHelper::map(WebRecord::find()->activeStatus()->all(), 'id', 'title');
    }

    /**
     * @param bool $empty indicates compare value
     * @return bool
     */
    public static function existsMoreMenuRecords($empty = true)
    {
	    if (!$web_id = Yii::$app->session->get('web_id'))
		    $web_id = WebRecord::getMainWebId();
        $count = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM menu WHERE web_id = :web_id', [':web_id' => $web_id])->queryScalar();
        $compareValue = $empty ? '0' : '1';
        return $count > $compareValue ? true : false;
    }

	/**
	 * Gets ID of main menu of given web
	 * @return bool|null|string
	 */
	public static function getMainMenuId() {
		if (!$web_id = Yii::$app->session->get('web_id'))
			$web_id = WebRecord::getMainWebId();
		return Yii::$app->db->createCommand('SELECT id FROM menu WHERE main = 1 AND web_id = :web_id', [
			':web_id' => $web_id
		])->queryScalar();
	}

	/**
	 * Gets menu options for dropdown
	 * @return array
	 */
	public static function getMenuOptions() {
		if (!$web_id = Yii::$app->session->get('web_id'))
			$web_id = WebRecord::getMainWebId();
		return ArrayHelper::map(self::find()->andWhere(['web_id' => $web_id])->orderBy('main DESC')->all(), 'id', 'title');
	}

	/**
	 * Initializes boxes property
	 * @param bool $insert
	 */
	public function initBoxes($insert = true) {
		if ($insert) {
			if (MenuRecord::existsMoreMenuRecords() === false) {
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
