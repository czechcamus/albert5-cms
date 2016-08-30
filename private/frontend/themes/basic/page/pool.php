<?php
/* @var $this yii\web\View */
/* @var $menuContent \frontend\models\MenuContent */
/* @var $page \common\models\Page */

use frontend\components\AdditionalField;
use frontend\components\CategoryArticlesList;
use frontend\components\InvitationsList;
use frontend\components\Weather;
use frontend\components\WebCamera;
use frontend\utilities\FrontEndHelper;
use pavlinter\display\DisplayImage;
use yii\helpers\Html;

$this->title                 = $menuContent->title;
$this->params['menuContent'] = $menuContent;
$page                        = $menuContent->content;

/** @noinspection PhpUndefinedFieldInspection */
$this->context->layout = 'pool-content';

if ( $page->image ) {
	echo DisplayImage::widget( [
		'options'  => [
			'class' => 'responsive-img',
			'title' => $menuContent->title
		],
		'category' => 'all',
		'image'    => $page->image->filename
	] );
}
?>
	<div class="row">
		<div class="col s12">
			<div class="info-page">
				<p>
					<?= AdditionalField::widget([
						'pageId' => $page->id,
						'addFieldId' => Yii::$app->params[Yii::$app->language]['statusAddFieldId'],
						'viewName' => 'todayStatus'
					]); ?> <span>&#9724;</span>
					<?= AdditionalField::widget([
						'pageId' => $page->id,
						'addFieldId' => Yii::$app->params[Yii::$app->language]['openTimeAddFieldId'],
						'viewName' => 'poolFields'
					]); ?><br />
					<?= Weather::widget(
						['viewName' => 'poolWeather']
					); ?>
					<?= AdditionalField::widget([
						'pageId' => $page->id,
						'addFieldId' => Yii::$app->params[Yii::$app->language]['watterTempAddFieldId'],
						'viewName' => 'poolFields'
					]); ?>
				</p>
			</div>
		</div>
	</div>


<?php
if ( $page->perex ) {
	echo '<div class="perex">' . $page->perex . '</div>';
} ?>

<div class="row">
	<div class="col s12 m6 media">
		<h3 class="fourth-color"><i class="material-icons">photo_camera</i> <?= Yii::t('front', 'Webcamera'); ?></h3>
		<?= WebCamera::widget(); ?>
	</div>
	<div class="col s12 m6 media">
		<h3 class="third-color"><i class="material-icons">visibility</i> <?= Yii::t('front', 'Virtual tour'); ?></h3>
		<?= Html::a(Html::img(Yii::$app->request->baseUrl . '/basic-assets/img/temp/virtual.jpg', [
			'class' => 'responsive-img'
		]), 'https://maps.google.cz/maps?cbll=49.083621,15.422501&layer=c&panoid=2Cu2OKFghRgAAAQfCLa2ew&cbp=13,210.44,,0,29.3&ie=UTF8&t=m&brcurrent=5,0,1&ll=49.016369,15.463943&spn=0.141405,0.385895&z=11&source=embed'); ?>
	</div>
</div>

<?php
if ( $page->description ) {
	echo '<div class="description" style="margin-bottom: 2rem">';
	echo FrontEndHelper::parseContent( $page->description );
	echo '</div>';
}

echo InvitationsList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['poolInvitationsCategoryId'],
	'viewName' => 'invitations',
	'wordsCount' => 30,
	'withImage' => true,
	'maxImageWidth' => 200,
	'columnsCount' => 1,
	'imageEdgeRatio' => 0.5,
	'noMessage' => true
]);

echo CategoryArticlesList::widget([
	'categoryId' => Yii::$app->params[Yii::$app->language]['poolActualitiesCategoryId'],
	'viewName' => 'actualities',
	'wordsCount' => 50,
	'withImage' => true,
	'maxImageWidth' => 200,
	'imageEdgeRatio' => 0.5,
	'noMessage' => true
]);