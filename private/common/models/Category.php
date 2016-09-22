<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.2.2015
 * Time: 9:35
 */

namespace common\models;


use common\utilities\MenuItemsReset;
use common\utilities\ModelTypeAttribute;
use common\utilities\RelationsDelete;
use common\utilities\UniqueMainProperty;
use yii\helpers\ArrayHelper;

/**
 * Class Category
 * @package common\models
 *
 * @property Image $image
 * @property ArticleCategory[] $articleCategories
 * @property MenuItemContent[] $menuItemContents
 * @property Article[] $articles
 */
class Category extends CategoryRecord
{
	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors()
	{
		$parentBehaviors = parent::behaviors();
		$thisBehaviors = [
			'main' => [
				'class' => UniqueMainProperty::className(),
				'filterAttributes' => [
					[
						'attribute' => 'category_type',
						'operator' => '='
					],
					[
						'attribute' => 'language_id',
						'operator' => '='
					],
				]
			],
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'category_type',
				'attributeValue' => self::TYPE_CATEGORY
			],
			'menuItemsReset' => MenuItemsReset::className(),
			'relationDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['articleCategories', 'menuItemContents']
			],
		];
		return ArrayHelper::merge($parentBehaviors, $thisBehaviors);
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['category_type' => self::TYPE_CATEGORY]);
	}

	/**
	 * Returns main category for given language
	 * @param integer $languageId
	 * @return bool|string
	 */
	public static function getMainCategory($languageId) {
		return self::find()->andWhere([
			'main' => 1,
			'language_id' => $languageId
		])->scalar();
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImage() {
		return $this->hasOne(Image::className(), ['id' => 'image_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticleCategories()
	{
		return $this->hasMany(ArticleCategory::className(), ['category_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticles() {
		return $this->hasMany(Article::className(), ['id' => 'article_id'])->via('articleCategories')->orderBy(['updated_at' => SORT_DESC]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMenuItemContents() {
		return $this->hasMany(MenuItemContent::className(), ['category_id' => 'id']);
	}
}