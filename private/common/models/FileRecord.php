<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 4.7.2015
 * Time: 16:01
 */

namespace common\models;

use common\utilities\FileDelete;
use common\utilities\RelationsDelete;
use common\utilities\StatusQuery;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * This is the model class for table "file".
 *
 * @property integer $id
 * @property string $title
 * @property integer $type
 * @property string $filename
 * @property integer $public
 * @property integer $file_time
 *
 * @property FileTextRecord[] $fileTexts
 */
class FileRecord extends ActiveRecord
{
	const TYPE_IMAGE = 1;
	const TYPE_FILE = 2;
	const TYPE_SOUND = 3;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'file';
	}

	/**
	 * @return array configuration of behaviors
	 */
	public function behaviors() {
		return [
			'relationsDelete' => [
				'class' => RelationsDelete::className(),
				'relations' => ['fileTexts']
			],
			'fileDelete' => FileDelete::className()
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['filename', 'required'],
			[['title', 'filename'], 'string'],
			[['type', 'public'], 'integer'],
			['file_time', 'safe']
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'title' => Yii::t('app', 'Title'),
			'type' => Yii::t('app', 'Type'),
			'filename' => Yii::t('app', 'Filename'),
			'public' => Yii::t('app', 'Public'),
			'file_time' => Yii::t('app', 'Modification time of file')
		];
	}

	/**
	 * @inheritdoc
	 * @return StatusQuery
	 */
	public static function find()
	{
		return new StatusQuery(get_called_class());
	}

	/**
	 * Gets file extensions for dropdown
	 * @return array
	 */
	public function getFileExtensionOptions() {
		return [
			'png' => 'PNG ' . Yii::t('app', 'images'),
			'jpg' => 'JPG ' . Yii::t('app', 'images'),
			'gif' => 'GIF ' . Yii::t('app', 'images'),
			'pdf' => 'PDF ' . Yii::t('app', 'files'),
			'mp3' => 'MP3 ' . Yii::t('app', 'sounds'),
			'm4a' => 'M4A ' . Yii::t('app', 'sounds'),
			'ogg' => 'OGG ' . Yii::t('app', 'sounds'),
			'oga' => 'OGA ' . Yii::t('app', 'sounds'),
		];
	}

	/**
	 * Synchronizes images on server with records in DB table
	 */
	public function synchronizeImages() {
		return $this->checkFS(Yii::$app->params['imageUploadDir'], Yii::$app->params['imageUploadDir'], strlen(Yii::$app->params['imageUploadDir']));
	}

	/**
	 * Synchronizes files on server with records in DB table
	 */
	public function synchronizeFiles() {
		return $this->checkFS(Yii::$app->params['fileUploadDir'], Yii::$app->params['fileUploadDir'], strlen(Yii::$app->params['fileUploadDir']));
	}

	/**
	 * Checks files on server against DB table
	 *
	 * @param string $dirName
	 * @param string $baseDirName
	 * @param integer $subsStringStart
	 * @param array $fileNames
	 *
	 * @return array of file names on server
	 */
	protected function checkFS($dirName, $baseDirName, $subsStringStart, $fileNames = []) {
		$dir = dir($dirName);
		while (($filename = $dir->read()) !== false) {
			if (is_dir($dirName . $filename)) {
				if ($filename != '.' && $filename != '..') {
					$fileNames = $this->checkFS($dirName . $filename . '/', $baseDirName, $subsStringStart, $fileNames);
				}
			} else {
				$title = strtolower(Inflector::humanize(strstr($filename, '.', true)));
				$filename = substr($dirName . $filename, $subsStringStart);
				$fileNames[] = $filename;
				$fileRecord = self::findOne(['filename' => $filename]);
				if (!$fileRecord) {
					$record = new FileRecord;
					$record->title = $title;
					$record->filename = $filename;
					$record->file_time = filemtime($baseDirName . $filename);
					$fileNameParts = explode('.', $filename);
					$record->type = self::getFileType(strtolower(array_pop($fileNameParts)));
					$record->save(false);
				}
			}
		}
		return $fileNames;
	}

	/**
	 * Checks existence of file on server, if not deletes table record
	 * @param $serverFiles
	 * @throws \Exception
	 */
	public function checkDB($serverFiles) {
		$fileRecords = self::find()->all();
		foreach ( $fileRecords as $fileRecord ) {
			/** @var $fileRecord FileRecord */
			if (!in_array($fileRecord->filename, $serverFiles)) {
				$fileRecord->delete();
			}
		}
	}

	/**
	 * Gets title of file in given language
	 * @param $languageId
	 * @return string
	 */
	public function getFileTitle($languageId) {
		$title = FileTextRecord::find()->select('title')->where([
			'file_id' => $this->id,
			'language_id' => $languageId
		])->scalar();
		return $title;
	}

	/**
	 * Gets description of file in given language
	 * @param $languageId
	 * @return string
	 */
	public function getFileDescription($languageId) {
		$description = FileTextRecord::find()->select('description')->where([
			'file_id' => $this->id,
			'language_id' => $languageId
		])->scalar();
		return $description;
	}

	/**
	 * Returns right file type number according to file extension
	 * @param $fileExt
	 * @return int|null
	 */
	public static function getFileType( $fileExt ) {
		$fileType = null;
		$images = ['png', 'jpg', 'gif'];
		$files = ['pdf'];
		$sounds = ['mp3', 'm4a', 'ogg', 'oga'];
		switch ($fileExt) {
			case (in_array($fileExt, $images)):
				$fileType = self::TYPE_IMAGE;
				break;
			case (in_array($fileExt, $files)):
				$fileType = self::TYPE_FILE;
				break;
			case (in_array($fileExt, $sounds)):
				$fileType = self::TYPE_SOUND;
				break;
		}
		return $fileType;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFileTexts()
	{
		return $this->hasMany(FileTextRecord::className(), ['file_id' => 'id']);
	}
}