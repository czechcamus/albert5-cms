<?php
/* @var $this yii\web\View */

$this->title = Yii::t('front', 'Information');
?>

<div class="row content">
	<div class="col s12">

		<?php if (Yii::$app->session->hasFlash('info')): ?>
			<div class="row">
				<div class="col s12 m6">
					<div class="card-panel">
						<?= Yii::$app->session->getFlash('info'); ?>
					</div>
				</div>
			</div>
		<?php else: ?>
			<p><?= Yii::t('front', 'No information'); ?></p>
		<?php endif; ?>

	</div>
</div>