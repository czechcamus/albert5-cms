<?php

namespace common\models;

use common\utilities\UniqueMainProperty;
use common\utilities\StatusQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string $title
 * @property string $acronym
 * @property integer $main
 * @property integer $active
 *
 * @property CategoryRecord[] $categories
 * @property ContentRecord[] $contents
 */
class LanguageRecord extends ActiveRecord
{

	/** @var array boxes of properties */
	public $boxes = [];

	const PROPERTY_MAIN = 1;
	const PROPERTY_ACTIVE = 2;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'language';
    }

	/**
	 * @return array configuration of behaviors.
	 */
	public function behaviors()
	{
		return [
			'main' => UniqueMainProperty::className(),
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'acronym'], 'required'],
            [['main', 'active'], 'integer'],
	        ['boxes', 'safe'],
            [['title'], 'string', 'max' => 20],
            [['acronym'], 'string', 'min' => 2, 'max' => 2],
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
            'acronym' => Yii::t('app', 'Acronym'),
            'main' => Yii::t('app', 'Main'),
            'active' => Yii::t('app', 'Active'),
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
     * @param bool $active if true, counts only active records
     * @return bool
     */
    public static function existsMoreLanguageRecords($empty = true, $active = false)
    {
        if ($active)
            $query = 'SELECT COUNT(*) FROM language WHERE active = 1';
        else
            $query = 'SELECT COUNT(*) FROM language';
        $count = Yii::$app->db->createCommand($query)->queryScalar();
        $compareValue = $empty ? '0' : '1';
        return $count > $compareValue ? true : false;
    }

    /**
     * Gets array of language values
     * @param null $language_id
     * @return array $row
     */
    public static function getLanguageValues($language_id = null)
    {
        if ($language_id === null)
            $language_id = self::getMainLanguageId();

        return Yii::$app->db->createCommand('SELECT * FROM language WHERE id = :lid', [':lid' => $language_id])->queryOne();
    }

    /**
     * @param bool $active
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getLanguages($active = false)
    {
        $query = self::find();
        if ($active)
            $query->activeStatus();
        return $query->all();
    }

    /**
     * Gets ID of main language
     * @return string
     */
    public static function getMainLanguageId()
    {
        return Yii::$app->db->createCommand('SELECT id FROM language WHERE main = 1')->queryScalar();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(CategoryRecord::className(), ['language_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContents()
    {
        return $this->hasMany(ContentRecord::className(), ['language_id' => 'id']);
    }
	/**
	 * Initializes boxes property
	 * @param bool $insert
	 */
	public function initBoxes($insert = true) {
		if ($insert) {
            if ($this->existsMoreLanguageRecords() === false) {
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
}
