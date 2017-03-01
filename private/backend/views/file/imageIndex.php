<?php

use backend\components\LanguageButtonDropdown;
use common\models\LanguageRecord;
use kop\y2sp\ScrollPager;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ImageSearch */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $form yii\bootstrap\ActiveForm */

$session = Yii::$app->session;
if (!$session['language_id'])
	$session['language_id'] = LanguageRecord::getMainLanguageId();

$this->title = Yii::t('back', 'Images');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-index">

	<?php
	if(LanguageRecord::existsMoreLanguageRecords(false, true)) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget([
			'routeBase' => ['file/images']
		]);
		echo '</div>';
	}
	?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="show-loading">
        <?= Html::a(Yii::t('back', 'Manage image files'), ['images-manage'], [
            'class' => 'btn btn-primary'
        ]); ?>
    </p>

	<?= $this->render('_filterForm', compact('searchModel')); ?>

    <?= ListView::widget([
		'dataProvider' => $dataProvider,
		'itemView' => '_image',
	    'viewParams' => ['lid' => $session['language_id']],
		'layout' => "{summary}\n<div class=\"row\">{items}</div>\n{pager}",
		'pager' => [
			'class' => ScrollPager::className(),
			'item' => '.file-view',
			'triggerText' => Yii::t('back', 'Load next images'),
			'noneLeftText' => ''
		]
    ]); ?>
</div>
