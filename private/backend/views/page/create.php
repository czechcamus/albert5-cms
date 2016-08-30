<?php
/* @var $this yii\web\View */
/* @var $model common\models\Page */

use yii\helpers\Html;

$modelClass = Yii::t('back', 'Page');
$this->title = Yii::t('back', 'Create {modelClass}', compact('modelClass'));
$this->params['breadcrumbs'][] = ['label' => Yii::t('back', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', compact('model')) ?>

</div>
