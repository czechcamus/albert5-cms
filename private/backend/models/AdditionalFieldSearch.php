<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 15.6.2016
 * Time: 16:18
 */

namespace backend\models;


use common\models\AdditionalFieldRecord;
use common\models\LanguageRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AdditionalFieldSearch represents the model behind the search form about `common\models\AdditionalFieldRecord`.
 */
class AdditionalFieldSearch extends AdditionalFieldRecord
{
	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = AdditionalFieldRecord::find();

		if (!isset($params['sort'])) $query->orderBy(['label' => SORT_ASC]);

		$session = Yii::$app->session;
		if (!$session['language_id'])
			$session['language_id'] = LanguageRecord::getMainLanguageId();

		$query->andWhere(['language_id' => $session['language_id']]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);
		
		return $dataProvider;
	}
}