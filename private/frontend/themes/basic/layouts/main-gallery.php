<?php
/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\basic\GalleryAsset;
use yii\helpers\Html;
use yii\helpers\Url;

GalleryAsset::register($this);

$this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <header class="page-row">
	    <div class="container">
		    <div class="row">
			    <div class="col s12">
				    <div class="page-title">
					    <h2>
						    <span class="subheader right"><?= Html::a(Yii::t('front', 'close') . ' <i class="material-icons">cancel</i>', Url::previous('page')); ?></span>
						    <?= ucfirst($this->params['title']); ?>
					    </h2>
				    </div>
			    </div>
		    </div>
	    </div>
    </header>

    <main class="page-row page-row-expanded">
	    <div class="container">
		    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
			    <div class="slides"></div>
			    <h3 class="title"></h3>
			    <a class="prev">‹</a>
			    <a class="next">›</a>
			    <a class="close">×</a>
			    <ol class="indicator"></ol>
		    </div>
		    <?= $content; ?>
		</div>
    </main>
    
    <footer class="page-row">
        <div class="container copyright">
	        <div class="row" style="margin-bottom: 0;">
		        <div class="col s12 m4">
			        <p>&copy; <?= Yii::$app->params['webOwner'] . ' ' . date( 'Y' ) ?></p>
		        </div>
		        <div class="col s12 m4">
			        <p class="center-align"><?= Yii::powered() ?></p>
				</div>
		        <div class="col s12 m4">
			        <p class="right-align">Webdesign by <a href="http://www.camus.cz">C@mus</a></p>
		        </div>
	        </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>
