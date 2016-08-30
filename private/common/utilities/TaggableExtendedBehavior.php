<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 13.9.2015
 * Time: 16:30
 */

namespace common\utilities;


use common\models\ContentRecord;
use creocoder\taggable\TaggableBehavior;
use yii\db\ActiveRecord;

class TaggableExtendedBehavior extends TaggableBehavior
{
	/**
	 * @return void
	 */
	public function afterSave()
	{
		$tagValues = $this->getTagValues(true);

		if ($tagValues === null) {
			return;
		}

		if (!$this->owner->getIsNewRecord()) {
			$this->beforeDelete();
		}

		/** @var ContentRecord $contentClass */
		$contentClass = $this->owner;
		$tagRelation = $contentClass->getRelation($this->tagRelation);
		$pivot = $tagRelation->via->from[0];
		/* @var ActiveRecord $class */
		$class = $tagRelation->modelClass;
		$rows = [];

		foreach ($tagValues as $value) {
			/* @var ActiveRecord $tag */
			$tag = $class::findOne([$this->tagValueAttribute => $value]);

			if ($tag === null) {
				$tag = new $class();
				$tag->setAttribute($this->tagValueAttribute, $value);
			}

			if ($this->tagFrequencyAttribute !== false) {
				$frequency = $tag->getAttribute($this->tagFrequencyAttribute);
				$tag->setAttribute($this->tagFrequencyAttribute, ++$frequency);
			}

			// Appended by Camus for supporting languages
			$tag->setAttribute('language_id', $contentClass->language_id);

			if ($tag->save()) {
				$rows[] = [$contentClass->getPrimaryKey(), $tag->getPrimaryKey()];
			}
		}

		if (!empty($rows)) {
			$contentClass->getDb()
	            ->createCommand()
	            ->batchInsert($pivot, [key($tagRelation->via->link), current($tagRelation->link)], $rows)
	            ->execute();
		}
	}

}