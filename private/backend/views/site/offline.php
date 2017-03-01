<?php
/* @var $this yii\web\View */

$this->title = Yii::t( 'back', 'Albert 5 - content management system' );
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Yii::$app->params['cmsWebTitle']; ?></h1>
        <p class="lead"><?= Yii::t( 'back', 'Content management system' ) ?></p>
    </div>

    <div class="body-content">

        <div class="container recent-boxes">
            <div class="row">
                <div class="col-lg-12 recent-box">
                    <h2><?= Yii::t( 'back', 'System is under maintenance. Please try to login later...' ); ?></h2>
                </div>
            </div>
        </div>

    </div>
</div>
