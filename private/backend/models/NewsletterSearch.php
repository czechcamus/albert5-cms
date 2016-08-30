<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 26.9.2015
 * Time: 21:51
 */

namespace backend\models;


use common\models\LanguageRecord;
use common\models\Newsletter;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class NewsletterSearch extends Newsletter
{
	public $sendDateTime;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['active'], 'integer'],
			[['title', 'sendDateTime'], 'safe']
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
		$query = Newsletter::find();

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
			return $dataProvider;
		}

		$query->andFilterWhere([
			'active' => $this->active
		]);

		$query->andFilterWhere(['like', 'title', $this->title]);
		$query->andFilterWhere(['like', 'CONCAT(DATE_FORMAT(content_date,\'%d.%m.%Y\'),\' \',DATE_FORMAT(content_time,\'%H:%i\'))', $this->sendDateTime]);

		$dataProvider->sort->attributes['sendDateTime'] = [
			'asc' => ['content_date' => SORT_ASC, 'content_time' => SORT_ASC],
			'desc' => ['content_date' => SORT_DESC, 'content_time' => SORT_DESC]
		];

		return $dataProvider;
	}
}