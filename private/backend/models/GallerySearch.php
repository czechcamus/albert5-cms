<?php

namespace backend\models;

use common\models\Gallery;
use common\models\LanguageRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * GallerySearch represents the model behind the search form about `common\models\CategoryRecord`.
 */
class GallerySearch extends Gallery
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['public', 'active'], 'integer'],
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
        /** @var ActiveQuery $query */
        $query = Gallery::find();

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
            'public' => $this->public,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
