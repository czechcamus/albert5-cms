<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<section>
    <div class="container">
        <div class="page-title">
            <h2>
                <?= Html::encode(ucfirst( $this->title )); ?>
            </h2>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p><?= Yii::t('front', 'The above error occurred while the Web server was processing your request.'); ?></p>
        <p><?= Yii::t('front', 'Please contact us if you think this is a server error. Thank you.'); ?></p>
    </div>
</section>