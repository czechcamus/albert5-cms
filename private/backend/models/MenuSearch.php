<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 10:40
 */

namespace backend\models;


use common\models\MenuRecord;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class MenuSearch extends MenuRecord
{

	public function search($web_id)
	{
		/** @var ActiveQuery $query */
		$query = MenuRecord::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => false
		]);

		$query->andWhere(['web_id' => $web_id]);
		$query->orderBy(['main' => SORT_DESC]);

		return $dataProvider;
	}

}