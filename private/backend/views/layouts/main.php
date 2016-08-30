<?php

use backend\assets\BackendAsset;
use common\models\WebRecord;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

BackendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => Html::img('@web/images/design/logo-albert5.png', ['alt' => Yii::t('back', 'Image') . ' - ' . Yii::t('back', 'Albert 5 logo')]),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => Yii::t('back', 'Home'), 'url' => ['/site/index'], 'visible' => !Yii::$app->user->isGuest],
                ['label' => Yii::t('back', 'Content'), 'items' => [
                    ['label' => Yii::t('back', 'Articles'), 'url' => ['article/index']],
                    ['label' => Yii::t('back', 'Pages'), 'url' => ['page/index']],
                    '<li class="divider"></li>',
                    ['label' => Yii::t('back', 'Menu items'), 'url' => ['menu-item/index'], 'visible' => Yii::$app->user->can('manager')],
                    '<li class="divider"></li>',
                    ['label' => Yii::t('back', 'Categories'), 'url' => ['category/index']],
                    ['label' => Yii::t('back', 'Galleries'), 'url' => ['gallery/index']],
                    '<li class="divider"></li>',
                    ['label' => Yii::t('back', 'Polls'), 'url' => ['poll/index']],
                    '<li class="divider"></li>',
                    ['label' => Yii::t('back', 'Images'), 'url' => ['file/images']],
                    ['label' => Yii::t('back', 'Files'), 'url' => ['file/files']],
                ], 'visible' => !Yii::$app->user->isGuest],
                ['label' => Yii::t('back', 'Manage newsletter'), 'items' => [
                    ['label' => Yii::t('back', 'Newsletters'), 'url' => ['newsletter/index'], 'visible' => (Yii::$app->user->can('manager') && WebRecord::existsMoreWebRecords())],
                    ['label' => Yii::t('back', 'Emails'), 'url' => ['email/index'], 'visible' => Yii::$app->user->can('manager')],
                ], 'visible' => (isset(Yii::$app->params['backendModules']['newsletter']) && Yii::$app->user->can('manager'))],
                ['label' => Yii::t('back', Yii::t('back', 'Admin')), 'items' => [
                    ['label' => Yii::t('back', 'Menus'), 'url' => ['menu/index'], 'visible' => (Yii::$app->user->can('admin') && WebRecord::existsMoreWebRecords())],
                    ['label' => Yii::t('back', 'Webs'), 'url' => ['web/index'], 'visible' => Yii::$app->user->can('admin')],
                    Yii::$app->user->can('admin') ? '<li class="divider"></li>' : '',
                    ['label' => Yii::t('back', 'Layouts'), 'url' => ['layout/index'], 'visible' => Yii::$app->user->can('admin')],
                    ['label' => Yii::t('back', 'Additional fields'), 'url' => ['additional-field/index'], 'visible' => Yii::$app->user->can('admin')],
                    ['label' => Yii::t('back', 'Languages'), 'url' => ['language/index'], 'visible' => Yii::$app->user->can('admin')],
                    Yii::$app->user->can('admin') ? '<li class="divider"></li>' : '',
                    ['label' => Yii::t('back', 'Users'), 'url' => ['user/index'], 'visible' => Yii::$app->user->can('admin')],
                ], 'visible' => Yii::$app->user->can('admin')],
                ['label' => Yii::t('back', Yii::t('back', 'Webs')), 'items' => WebRecord::getNavBarItems(), 'visible' => WebRecord::existsMoreWebRecords()]
            ];
            if (Yii::$app->user->isGuest) {
                /** @noinspection PhpUndefinedFieldInspection */
                $menuItems[] = [ 'label' => Yii::t('back','Login'), 'url' => ['/site/login'], 'visible' => $this->context->action->id != 'login'];
            } else {
                /** @noinspection PhpUndefinedFieldInspection */
                $menuItems[] = [
                    'label' => Yii::t('back','Logout') . ' (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; <?= Yii::$app->params['cmsWebTitle']; ?> <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php Modal::begin([
	    'headerOptions' => [
		    'id' => 'modalHeader'
	    ],
	    'id' => 'modal',
	    'size' => 'modal-lg',
	    'clientOptions' => [
		    'backdrop' => 'static',
		    'keyboard' => false
	    ]
    ]);

    echo '<div id="modalContent"></div>';

    Modal::end(); ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
