<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $page \common\models\Page */


$page = $content->content;

echo $this->renderFile( '@frontend/themes/basic/page/pdf/page.php', compact('content', 'page'));