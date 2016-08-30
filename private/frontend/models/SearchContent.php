<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 6.10.2015
 * Time: 8:01
 */

namespace frontend\models;


use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\StringHelper;

class SearchContent extends Model
{
	public $target;
	public $q;

	public $items = [];

	const TARGET_WEB = 1;
	const TARGET_CATALOG = 2;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['q', 'required'],
			['q', 'string', 'min' => 3],
			['target', 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'target' => Yii::t('front', 'Target of searching'),
			'q' => Yii::t('front', 'Searched expression'),
		];
	}

	/**
	 * Gets founded items array data provider
	 * @param bool $rss
	 * @return ArrayDataProvider
	 */
	public function getItems($rss = false) {
		if (!$rss) {
			$menuCategories = (new MenuContent())->searchCategory($this->q);
			$this->setItems($menuCategories, 'category');
		}
		$menuContents = (new MenuContent())->searchContent($this->q);
		$this->setItems($menuContents, 'content');
		$articleContents = (new ArticleContent())->search($this->q);
		$this->setItems($articleContents, 'article');

		return new ArrayDataProvider([
			'allModels' => $this->items
		]);
	}

	/**
	 * Gets founded items array data provide
	 * @param $tag
	 * @return ArrayDataProvider
	 */
	public function getTagItems( $tag ) {
		$menuContents = (new MenuContent())->searchTags($tag);
		$this->setItems($menuContents, 'content');
		$articleContents = (new ArticleContent())->searchTags($tag);
		$this->setItems($articleContents, 'article');

		return new ArrayDataProvider([
			'allModels' => $this->items
		]);
	}

	/**
	 * Sets found items array
	 * @param $data
	 * @param $type
	 */
	private function setItems($data, $type) {
		foreach ( $data as $item ) {
			$this->items[] = [
				'id' => $type != 'article' ? $item->id : $item['article_id'],
				'title' => $type != 'article' ? $item->title : $item['article_title'],
				'breadcrumbs' => MenuContent::getBreadCrumbs($type != 'article' ? $item->parent_id : $item['menu_item_id'], [], false),
				'perex' => StringHelper::truncateWords(($type == 'category' ? $item->category->description : ($type == 'content' ? ($item->content->perex ? $item->content->perex : $item->content->description) : $item['perex'])), 30),
				'url' => $type != 'article' ? $item->getUrl() : ArticleContent::getUrl($item['menu_item_title'], $item['menu_item_id'], $item['article_title'], $item['article_id']),
				'updated_at' => $type != 'article' ? $item->updated_at : $item['article_updated_at']
			];
		}
	}
}