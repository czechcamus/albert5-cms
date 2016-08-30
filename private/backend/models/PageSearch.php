<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.6.2015
 * Time: 13:31
 */

namespace backend\models;


use common\models\LanguageRecord;
use common\models\Page;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class PageSearch extends Page
{
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['active', 'public'], 'integer'],
			[['title'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function search( $params ) {
		/** @var ActiveQuery $query */
		$query = Page::find();

		if (!isset($params['sort'])) $query->orderBy(['updated_at' => SORT_DESC]);

		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$query->andWhere(['language_id' => $session['language_id']]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
			'active' => $this->active,
			'public' => $this->public
		]);

		$query->andFilterWhere(['like', 'title', $this->title]);

		return $dataProvider;
	}
}