<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 20.9.2015
 * Time: 21:00
 */

namespace backend\models;

use common\models\LanguageRecord;
use common\models\EmailRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class EmailSearch extends EmailRecord
{
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['active'], 'integer'],
			[['email'], 'safe']
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
		$query = EmailRecord::find();

		if (!isset($params['sort'])) $query->orderBy(['created_at' => SORT_DESC]);

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
			'active' => $this->active
		]);

		$query->andFilterWhere(['like', 'email', $this->email]);

		return $dataProvider;
	}
}