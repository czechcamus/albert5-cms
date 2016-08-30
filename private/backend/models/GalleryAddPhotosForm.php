<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 22.7.2015
 * Time: 14:23
 */

namespace backend\models;


use common\models\Image;
use common\models\ImageGallery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class GalleryAddPhotosForm extends Model
{
	/** @var integer actual gallery id */
	public $item_id;
	/** @var array ids of used images */
	public $usedImagesIds = [];
	/** @var array ids of added images */
	public $addedImagesIds = [];

	/**
	 * GalleryAddPhotosForm constructor
	 * @param integer $item_id
	 */
	public function __construct( $item_id ) {
		parent::__construct();
		$this->item_id = $item_id;
		$this->setUsedImagesIds();
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['addedImagesIds', 'safe']
		];
	}

	/**
	 * Creates data provider instance
	 * @return ActiveDataProvider
	 */
	public function search() {
		$query = Image::find()->andWhere(['not in', 'id', $this->usedImagesIds]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => [
				'defaultOrder' => [
					'file_time' => SORT_DESC
				]
			],
          'pagination' => [
                'pageSize' => 24,
            ]
		]);
		return $dataProvider;
	}

	/**
	 * Saves relations between added photos and actual gallery
	 */
	public function savePhotosToGallery() {
		$maxOrder = ImageGallery::find()->where(['gallery_id' => $this->item_id])->max('item_order');
		foreach ( array_keys($this->addedImagesIds) as $image_id ) {
			$imageGallery = new ImageGallery;
			$imageGallery->gallery_id = $this->item_id;
			$imageGallery->image_id = $image_id;
			$imageGallery->item_order = ++$maxOrder;
			$imageGallery->save();
		}
	}

	/**
	 * Sets usedImagesIds property
	 */
	private function setUsedImagesIds() {
		// clean junction table
		ImageGallery::deleteAll('image_id IS NULL');
		// return array populated with image_ids
		$this->usedImagesIds = ImageGallery::find()
				->select('image_id')
				->andWhere(['gallery_id' => $this->item_id])
				->column();
	}
}