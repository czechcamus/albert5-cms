<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 21.7.2015
 * Time: 21:36
 */

namespace backend\models;


use common\models\Gallery;
use common\models\ImageGallery;
use pavlinter\display\DisplayImage;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class GalleryPhotosForm extends Model
{
	/** @var integer actual gallery id */
	public $item_id;
	/** @var integer language id */
	public $language_id;
	/** @var string title of category */
	public $title;
	/** @var string description of category */
	public $description;
	/** @var string order of menu item Ids delimited by comma */
	public $item_order;
	/** @var array gallery photos */
	public $photos = [];

	/**
	 * GalleryPhotosForm constructor
	 * @param integer $item_id
	 */
	public function __construct( $item_id ) {
		parent::__construct();
		/** @var $gallery Gallery */
		$gallery = Gallery::findOne($item_id);
		$this->item_id = $gallery->id;
		$this->language_id = $gallery->language_id;
		$this->title = $gallery->title;
		$this->description = $gallery->description;
		$this->setPhotos();
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['item_order', 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'item_order' => Yii::t('back', 'Order')
		];
	}

	/**
	 * Returns array of photos with delete button of given gallery
	 * @return array
	 */
	public function getPhotosWithControl() {
		$imageGalleries = ImageGallery::find()->andWhere(['gallery_id' => $this->item_id])->orderBy('item_order')->all();
		$photosArray = [];
		/** @var ImageGallery $imageGallery */
		foreach ( $imageGalleries as $imageGallery ) {
			if ($imageGallery->image)
				$photosArray[$imageGallery->id] = ['content' => $this->renderPhotoWithControl($imageGallery->image)];
		}
		return $photosArray;
	}

	/**
	 * Sets array of photos
	 */
	private function setPhotos() {
		/** @var Gallery $gallery */
		$gallery = Gallery::findOne($this->item_id);
		$this->photos = $gallery->images;
	}

	/**
	 * Renders photo with remove btn
	 * @param $photo
	 * @return string
	 */
	private function renderPhotoWithControl( $photo ) {
		$html = Html::a('<span class="glyphicon glyphicon-remove" title="' . Yii::t('back', 'remove photo from gallery') . '"></span>', ['remove-photo', 'gid' => $this->item_id, 'pid' => $photo->id]);
		$html .= DisplayImage::widget([
			'width' => 100,
			'height' => 100,
			'image' => $photo->filename,
			'category' => 'all'
		]);
		return $html;
	}
}