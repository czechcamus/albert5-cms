<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $this->beginPage() ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>
<body style="font-family: sans-serif;">
    <?php $this->beginBody() ?>
    <?php if ($this->params['viewMail'] === true) {
        echo '<p>' . Yii::t('back', 'If newsletter looks not good you can') . ' <strong>' .  Html::a(Yii::t('back', 'view him in browser'), Url::to(['view', 'id' => $this->params['id']], true)) . '</strong></p>';
	} ?>
    <table cellpadding="0" cellspacing="0" border="0" style="width: 70%; margin: 30px auto 30px auto;">
	    <thead>
	        <tr>
		        <th style="text-align: left"><?= Html::a(Html::img(Url::home(true) . '../basic-assets/img/knihovna-logo.png'), Url::home(true) . '../'); ?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr>
		        <td>
		            <?= $content; ?>
		        </td>
	        </tr>
	    </tbody>
    </table>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>