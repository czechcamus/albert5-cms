<?php

namespace backend\models;

use common\models\Category;
use common\models\LanguageRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CategorySearch represents the model behind the search form about `common\models\CategoryRecord`.
 */
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['main', 'public', 'active'], 'integer'],
            [['title'], 'safe'],
        ];
    }

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
        $query = Category::find();

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
            'main' => $this->main,
            'public' => $this->public,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
