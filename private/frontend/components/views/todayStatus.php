<?php
/* @var $this \yii\base\View */
/* @var $field \common\models\PageFieldRecord*/

echo Yii::t('front', 'Today') . ' ' . date('d.m.Y', time()) . ': <strong>' . $field->content . '</strong>';