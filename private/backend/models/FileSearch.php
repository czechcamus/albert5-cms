<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 12.11.2015
 * Time: 7:39
 */

namespace backend\models;


use common\models\FileRecord;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class FileSearch extends FileRecord
{
	/** @var string name of file without directories */
	public $fileTitle;

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['fileTitle', 'string']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return array_merge(parent::attributeLabels(), [
			'fileTitle' => Yii::t('back', 'Title of source file')
		]);
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
	public function search($params) {

		$query = FileRecord::find()->where(['not', 'type=:image'])->params([
			':image' => FileRecord::TYPE_IMAGE
		]);

		if ( ! isset( $params['sort'] ) ) {
			$query->orderBy( [ 'file_time' => SORT_DESC ] );
		}

		$dataProvider = new ActiveDataProvider( [
			'query'      => $query,
			'pagination' => [
				'pageSize' => 24,
			],
			'sort'       => [
				'defaultOrder' => [
					'file_time' => SORT_DESC
				]
			]
		] );

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'title', $this->fileTitle]);

		return $dataProvider;
	}
}