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
use Yii;
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
		]);
		return $dataProvider;
	}

	/**
	 * Saves relations between added photos and actual gallery
	 */
	public function savePhotosToGallery() {
		$countNewImages = count($this->addedImagesIds);
		Yii::$app->db->createCommand('UPDATE image_gallery SET item_order=item_order+:addition WHERE gallery_id=:id')
			->bindValue(':id', $this->item_id)
			->bindValue(':addition', $countNewImages)
			->execute();
		$i = 0;
		foreach ( $this->addedImagesIds as $image_id ) {
			$imageGallery = new ImageGallery;
			$imageGallery->gallery_id = $this->item_id;
			$imageGallery->image_id = $image_id;
			$imageGallery->item_order = ++$i;
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