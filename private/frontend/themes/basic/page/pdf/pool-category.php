<?php
/* @var $this yii\web\View */
/* @var $content \frontend\models\MenuContent */
/* @var $category \common\models\Category */

echo $this->renderFile( '@frontend/themes/basic/page/pdf/category.php', compact('content', 'category'));

