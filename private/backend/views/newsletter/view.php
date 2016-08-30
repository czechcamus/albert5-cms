<?php
/* @var $this yii\web\View */
/* @var $model common\models\Newsletter */
/* @var $viewMail boolean */

use backend\utilities\BackendHelper;
use yii\helpers\Html;

$modelClass = Yii::t('back', 'Newsletter');
$this->title = Yii::t('back', 'View {modelClass}: ', compact('modelClass')) . ' - ' . $model->title;
$this->params['viewMail'] = $viewMail;
$this->params['id'] = $model->id;
?>

<h1 style="font-family: Impact, 'Techno CE', sans-serif; font-weight: normal; font-size: 32px; color: white; background-color: #024A80; padding: 5px 15px 5px 15px;"><?= Html::encode($model->title); ?></h1>

<?= BackendHelper::parseContent($model->description); ?>

