<?php
/* @var $this yii\web\View */
/* @var $model backend\models\NewsletterForm */

use yii\helpers\Html;

$modelClass = Yii::t('back', 'Newsletter');
$this->title = Yii::t('back', 'Create {modelClass}', compact('modelClass'));
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Newsletters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', compact('model')) ?>

</div>
