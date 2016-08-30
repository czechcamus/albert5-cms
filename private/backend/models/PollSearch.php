<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.9.2015
 * Time: 21:00
 */

namespace backend\models;

use common\models\LanguageRecord;
use common\models\PollRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class PollSearch extends PollRecord
{
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['active', 'main'], 'integer'],
			[['question', 'end_date'], 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function search( $params ) {
		/** @var ActiveQuery $query */
		$query = PollRecord::find();

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
			'active' => $this->active,
			'main' => $this->main
		]);

		$query->andFilterWhere(['like', 'question', $this->question]);
		$query->andFilterWhere(['like', 'DATE_FORMAT(end_date,\'%d.%m.%Y\')', $this->end_date]);

		return $dataProvider;
	}
}