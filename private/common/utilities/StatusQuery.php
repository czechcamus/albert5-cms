<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 3.2.2015
 * Time: 9:59
 */

namespace common\utilities;


use creocoder\taggable\TaggableQueryBehavior;
use yii\db\ActiveQuery;

class StatusQuery extends ActiveQuery
{
	public function behaviors() {
		return [
			TaggableQueryBehavior::className()
		];
	}

	public function mainStatus($state = true)
	{
		$this->andWhere(['main' => $state]);
		return $this;
	}

	public function activeStatus($state = true)
	{
		$this->andWhere(['active' => $state]);
		return $this;
	}

	public function publicStatus($state = true)
	{
		$this->andWhere(['public' => $state]);
		return $this;
	}
}