<?php
/* @var $poll \common\models\PollRecord */
/* @var $chartOptions array */
/* @var $colWidth string */

use fruppel\googlecharts\GoogleCharts;
use yii\helpers\Html;
use yii\widgets\Pjax;


if ( $poll ) {
	$cookies = Yii::$app->request->cookies;
	$isVoted = $cookies->getValue( 'poll_' . $poll->id );

	echo '<div class="row">';
	echo '<div class="col ' . $colWidth . '">';
	echo '<div class="poll">';

	Pjax::begin();
	echo '<div class="row">';
	echo '<div class="col s12">';
	echo '<h3>' . Yii::t( 'front', 'Poll' ) . '</h3>';
	echo GoogleCharts::widget( $chartOptions );
	echo '</div>';
	echo '</div>';
	if ( ! $isVoted ) {
		echo '<div class="row">';
		echo '<div class="col s12">';
		echo '<button data-target="vote-modal" class="btn modal-trigger"><i class="material-icons right">thumb_up</i>' . Yii::t( 'front',
				'vote' ) . '</button>';
		echo '<div id="vote-modal" class="modal">';
		echo Html::beginForm( '@web/site/vote' );
		echo '<div class="modal-content">';
		echo '<h4>' . $poll->question . '</h4>';
		foreach ( $poll->answers as $answerPoll ) {
			echo Html::radio( 'answer', false, ['value' => $answerPoll->id, 'id' => 'answer_' . $answerPoll->id] ) . ' ' . Html::label( $answerPoll->answer,
					'answer_' . $answerPoll->id ) . '<br />';
		}
		echo '</div>';
		echo '<div class="modal-footer">';
		echo '<a href="#" class="modal-action modal-close waves-effect waves-green btn-flat">' . Yii::t( 'front',
				'Close' ) . '</a>';
		echo Html::submitButton( Yii::t( 'front', 'Vote' ), [
			'class' => 'modal-action waves-effect waves-red btn-flat'
		] );
		echo '</div>';
		echo Html::endForm();
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	Pjax::end();

	echo '</div>';
	echo '</div>';
	echo '</div>';
}
