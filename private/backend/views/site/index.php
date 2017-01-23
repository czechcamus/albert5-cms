<?php
/* @var $this yii\web\View */

use backend\components\RecentContent;
use yii\helpers\Html;

$this->title = Yii::t('back', 'Albert 5 - content management system');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::$app->params['cmsWebTitle']; ?></h1>
        <p class="lead"><?= Yii::t('back', 'Content management system') ?></p>
    </div>

    <?php if (Yii::$app->user->can('user')): ?>
    <div class="body-content">

	    <div class="container recent-boxes">
	        <div class="row">
	            <div class="col-lg-6 recent-box">
		            <?= RecentContent::widget(['itemClass' => 'Article']); ?>
	            </div>
	            <div class="col-lg-6 recent-box">
		            <?= RecentContent::widget(); ?>
	            </div>
	        </div>
	        <div class="row">
	            <div class="col-lg-12 recent-box">
		            <h2><?= Yii::t('back', 'Other content'); ?></h2>
		            <p class="text-center">
			            <?= Html::a(Yii::t('back', 'Manage menu items'), ['menu-item/index'], ['class' => 'btn btn-primary']); ?>
			            <?= Html::a(Yii::t('back', 'Manage categories'), ['category/index'], ['class' => 'btn btn-primary']); ?>
			            <?= Html::a(Yii::t('back', 'Manage photo galleries'), ['gallery/index'], ['class' => 'btn btn-primary']); ?>
			            <?= Html::a(Yii::t('back', 'Manage polls'), ['poll/index'], ['class' => 'btn btn-primary']); ?>
			            <?= Html::a(Yii::t('back', 'Manage images'), ['file/images'], ['class' => 'btn btn-primary']); ?>
			            <?= Html::a(Yii::t('back', 'Manage files'), ['file/files'], ['class' => 'btn btn-primary']); ?>
		            </p>
	            </div>
	        </div>
	    </div>

    </div>
    <?php endif; ?>
</div>
