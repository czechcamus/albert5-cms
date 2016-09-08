<?php
/* @var $this yii\web\View */
/* @var $form frontend\utilities\MaterializeActiveForm */
/* @var $model frontend\models\SearchContent */

use common\models\LanguageRecord;
use frontend\models\SearchContent;
use frontend\utilities\MaterializeActiveForm;
use yii\helpers\Url;

$template      = "\n{input}\n{label}\n{hint}\n{error}";
$model->target = SearchContent::TARGET_WEB;
?>

<?php
$webUrlPart = Yii::$app->request->get( 'web', \Yii::$app->params['defaultWeb'] ) == 'main' ? '' : '/' . Yii::$app->request->get( 'web' );
$language = LanguageRecord::getLanguageValues();
$languageUrlPart = $language['main'] ? '' : '/' . $language['acronym'];
$form = MaterializeActiveForm::begin( [
	'action' => Url::toRoute( [ 'page/search' ] ) . $webUrlPart . $languageUrlPart,
	'method' => 'get',
	'id'     => 'search-form'
] ); ?>
<div class="row">
	<?= $form->field( $model, 'target',  [
		'template' => "\n{input}",
	] )->hiddenInput(); ?>
	<?= $form->field( $model, 'q', [
		'template' => $template,
		'options'  => [ 'class' => 'input-field search-field col s12' ]
	] ); ?>
</div>
<?php MaterializeActiveForm::end(); ?>
