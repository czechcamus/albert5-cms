<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 8.7.2015
 * Time: 5:16
 */

namespace common\models;

use common\utilities\ModelTypeAttribute;
use common\utilities\RelationsDelete;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for content article.
 *
 * @property ArticleCategory[] $articleCategories
 */
class Article extends ContentRecord
{
	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors() {
		$parentBehaviors = parent::behaviors();
		$thisBehaviors = [
			'type' => [
				'class' => ModelTypeAttribute::className(),
				'attribute' => 'content_type',
				'attributeValue' => self::TYPE_ARTICLE
			],
			'relationDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['articleCategories']
			]
		];
		return ArrayHelper::merge($parentBehaviors, $thisBehaviors);
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return parent::find()->andWhere(['content_type' => self::TYPE_ARTICLE]);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getArticleCategories()
	{
		return $this->hasMany(ArticleCategory::className(), ['article_id' => 'id']);
	}
}