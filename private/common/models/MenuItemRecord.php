<?php

namespace common\models;

use common\utilities\RelationsDelete;
use common\utilities\StatusQuery;
use common\utilities\UniqueMainProperty;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Inflector;

/**
 * This is the model class for table "menu_item".
 *
 * @property integer $id
 * @property string $title
 * @property integer $language_id
 * @property integer $menu_id
 * @property integer $parent_id
 * @property integer $layout_id
 * @property integer $active
 * @property integer $public
 * @property integer $main
 * @property string $link_url
 * @property string $link_target
 * @property integer $item_order
 * @property integer $content_type
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property UserRecord $createdBy
 * @property LanguageRecord $language
 * @property MenuRecord $menu
 * @property MenuItemRecord $parentItem
 * @property MenuItemRecord[] $childrenItems
 * @property UserRecord $updatedBy
 * @property MenuItemContent $menuItemContent
 * @property ContentRecord $content
 * @property Category $category
 * @property LayoutRecord $layout
 */
class MenuItemRecord extends ActiveRecord
{
    const CONTENT_PAGE = 1;
    const CONTENT_CATEGORY = 2;
    const CONTENT_LINK = 3;

	const TARGET_THIS_WINDOW = 1;
	const TARGET_NEW_WINDOW = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_item';
    }

	/**
	 * @return array configuration of behaviors.
	 */
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()')
			],
			'blame' => BlameableBehavior::className(),
			'main' => [
				'class' => UniqueMainProperty::className(),
				'filterAttributes' => [
					[
						'attribute' => 'menu_id',
						'operator' => '='
					],
					[
						'attribute' => 'language_id',
						'operator' => '='
					]
				]
			],
			'relationsDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['menuItemContent']
			]
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'language_id', 'menu_id', 'content_type'], 'required'],
            [['language_id', 'menu_id', 'parent_id', 'content_type', 'layout_id', 'link_target', 'active', 'public', 'main', 'item_order', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'item_order'], 'safe'],
            [['title', 'link_url'], 'string', 'max' => 255]
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
            'language_id' => Yii::t('app', 'Language ID'),
            'menu_id' => Yii::t('app', 'Menu ID'),
            'parent_id' => Yii::t('app', 'Parent Item ID'),
	        'content_type' => Yii::t('app', 'Content Type'),
            'layout_id' => Yii::t('app', 'Layout'),
	        'link_url' => Yii::t('app', 'Link Url'),
	        'link_target' => Yii::t('app', 'Link Target'),
            'active' => Yii::t('app', 'Active'),
            'public' => Yii::t('app', 'Public'),
            'main' => Yii::t('app', 'Main'),
            'item_order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
	 * Count item order value
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($insert) {
				$query = (new Query())->select('*')->from('menu_item')->where('menu_id=:menu_id', [':menu_id' => $this->menu_id]);
				if ($this->parent_id > 0)
					$query->andWhere('parent_id=:parent_id', [':parent_id' => $this->parent_id]);
				else
					$query->andWhere('ISNULL(parent_id)');
				$this->item_order = $query->count() + 1;
			}
			return true;
		} else {
			return false;
		}
	}

    /**
     * Cheks if is possible to delete item
     * @return bool
     */
    public function isDeletable()
    {
        return !$this->main && !$this->hasItems();
    }

    /**
     * Cheks if item has subitems
     * @return bool
     */
    public function hasItems()
    {
        $count = \Yii::$app->db->createCommand('SELECT COUNT(*) FROM menu_item WHERE parent_id = :id', [':id' => $this->id])
                               ->queryScalar();
        return $count != false;
    }

	/**
	 * Returns "url friendly" text without diacritics, spaces and so on
	 * @return string
	 */
	public function getMenuItemText() {
		return Inflector::slug(strip_tags($this->title));
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserRecord::className(), ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(LanguageRecord::className(), ['id' => 'language_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(MenuRecord::className(), ['id' => 'menu_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParentItem()
    {
        return $this->hasOne(MenuItemRecord::className(), ['id' => 'parent_id']);
    }

	/**
	 * @return ActiveQuery
	 */
	public function getLayout()
	{
		return $this->hasOne(LayoutRecord::className(), ['id' => 'layout_id']);
	}

    /**
     * @return ActiveQuery
     */
    public function getChildrenItems()
    {
        return $this->hasMany(MenuItemRecord::className(), ['parent_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserRecord::className(), ['id' => 'updated_by']);
    }

	/**
	 * @return ActiveQuery
	 */
	public function getMenuItemContent()
	{
		return $this->hasOne(MenuItemContent::className(), ['menu_item_id' => 'id']);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getContent()
	{
		return $this->hasOne(ContentRecord::className(), ['id' => 'content_id'])->via('menuItemContent');
	}

	/**
	 * @return ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'category_id'])->via('menuItemContent');
	}
}
