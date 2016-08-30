<?php
/* @var $this yii\web\View */
/* @var $content string */

use frontend\assets\basic\BasicContentAsset;
use frontend\components\RecentArticlesList;
use yii\helpers\Url;

BasicContentAsset::register( $this );

$this->beginContent( $this->theme->getPath( 'layouts/main.php' ) ); ?>

	<section>
		<div class="container">
			<div class="page-title">
				<h2>
					<?= ucfirst( $this->title ); ?>
				</h2>
			</div>
		</div>
	</section>

	<section>
		<div class="container">
			<div class="row content">
				<div class="col s12 m8">
					<div class="row">
						<div class="col s12">
							<?= $content; ?>
						</div>
					</div>
				</div>
				<div class="col s12 m4">
					<?= RecentArticlesList::widget(); ?>
					<?= $this->renderFile( '@frontend/themes/basic/components/_buttons.php', [
						'url' => Url::current( [ ], true )
					] ); ?>
				</div>
			</div>
		</div>
	</section>

<?php $this->endContent(); ?>