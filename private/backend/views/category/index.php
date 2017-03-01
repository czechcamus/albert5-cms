<?php

use backend\components\LanguageButtonDropdown;
use backend\models\CategoryForm;
use common\models\LanguageRecord;
use yii\helpers\Html;

/* @var $this yii\web\View */

$session = Yii::$app->session;
if ( ! $session['language_id'] ) {
	$session['language_id'] = LanguageRecord::getMainLanguageId();
}

$this->title                   = Yii::t( 'back', 'Categories' );
$this->params['breadcrumbs'][] = $this->title;
$modelClass                    = Yii::t( 'back', 'Category' );
?>
<div>

	<?php
	if ( $session['language_id'] || LanguageRecord::existsMoreLanguageRecords( false, true ) ) {

		echo '<div class="pull-right">';
		echo LanguageButtonDropdown::widget( [
			'routeBase' => [ 'category/index' ]
		] );
		echo '</div>';
	}
	?>

    <h1><?= Html::encode( $this->title ) ?></h1>

    <p class="show-loading">
		<?= Html::a(Yii::t( 'back', 'Create {modelClass}', compact( 'modelClass' ) ), ['create'],
			[
				'class' => 'btn btn-success'
			]
		) ?>
    </p>

	<?php if ( $session->hasFlash( 'info' ) ): ?>
        <div class="alert alert-success">
			<?= $session->getFlash( 'info' ); ?>
        </div>
	<?php endif; ?>

	<?php
    $lid = $session['language_id'];
    $listItems = CategoryForm::getCategoriesList($session['language_id']);
    echo '<table class="table table-striped table-bordered">';
    echo '<tr>';
    echo '<th>' . Yii::t('back', 'Title') . '</th>';
    echo '<th>' . Yii::t('back', 'Main') . '</th>';
    echo '<th>' . Yii::t('back', 'Public') . '</th>';
    echo '<th>' . Yii::t('back', 'Active') . '</th>';
    echo '<th>' . Yii::t('back', 'Number of articles') . '</th>';
    echo '<th>&nbsp;</th>';
    echo '</tr>';
    foreach ($listItems as  $listItem) {
        echo '<tr>';
        echo '<td>' . $listItem['title'] . '</td>';
        echo '<td>' . $listItem['main'] . '</td>';
        echo '<td>' . $listItem['public'] . '</td>';
        echo '<td>' . $listItem['active'] . '</td>';
        echo '<td>' . $listItem['articlesCount'] . '</td>';
        echo '<td>' . $listItem['buttons'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    ?>

</div>