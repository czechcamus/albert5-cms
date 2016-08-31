<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 30.8.2016
 * Time: 15:04
 */

namespace frontend\modules\install\models;


use frontend\modules\install\Module;

class FrontendForm extends InstallForm {
	// frontend
	public $maxDisplayImageWidth = 900;
	public $galleryLinkImageSize = 400;
	public $galleryThumbnailSize = 200;
	public $defaultTagsCount = 20;
	public $defaultTagItemSizeStep = 5;
	public $googleMapsKey = 'AIzaSyA-1jkGfN_9u8onoQytDufbAzK5eS2FYrY';

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'maxDisplayImageWidth',
					'galleryLinkImageSize',
					'galleryThumbnailSize',
					'defaultTagsCount',
					'defaultTagItemSizeStep',
					'googleMapsKey'
				],
				'required'
			],
			[
				[
					'maxDisplayImageWidth',
					'galleryLinkImageSize',
					'galleryThumbnailSize',
					'defaultTagsCount',
					'defaultTagItemSizeStep',
				],
				'integer'
			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'maxDisplayImageWidth'   => Module::t( 'inst', 'Maximal width of image in content' ),
			'galleryLinkImageSize'   => Module::t( 'inst', 'Gallery link image size' ),
			'galleryThumbnailSize'   => Module::t( 'inst', 'Gallery thumbnail size' ),
			'defaultTagsCount'       => Module::t( 'inst', 'Default count of displayed tags' ),
			'defaultTagItemSizeStep' => Module::t( 'inst', 'Step of tag item font size' ),
			'googleMapsKey'          => Module::t( 'inst', 'Google maps key' )
		];
	}

	public function save() {
		$this->setConfig( \Yii::getAlias( '@frontend' ) . '/config/params.php', [
			'googleMapsKey'
		] );
		$this->setConfig( \Yii::getAlias( '@frontend' ) . '/config/params.php', [
			'maxDisplayImageWidth',
			'galleryLinkImageSize',
			'galleryThumbnailSize',
			'defaultTagsCount',
			'defaultTagItemSizeStep'
		], false );
	}
}