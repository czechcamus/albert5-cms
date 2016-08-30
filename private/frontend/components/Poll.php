<?php
/**
 * Created by PhpStorm.
 * User: Pavel
 * Date: 22.9.2015
 * Time: 14:18
 */

namespace frontend\components;


use common\models\PollRecord;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class Poll displays poll and poll form
 * @property $id integer is ID of displayed poll defaults to main poll
 * @property $chartType string is Google Charts type defaults to BarChart
 * @package frontend\components
 */
class Poll extends Widget
{
	public $id;

	public $chartType = 'PieChart';

	public $colWidth = 's12';

	private $_poll;

	private $_chartOptions = [];

	public function init() {
		parent::init();
		if (!$this->id)
			$this->id = PollRecord::getMainPollId();
		if ($this->id) {
			$this->_poll = PollRecord::find()->activeStatus()
				->andWhere(['id' => $this->id])
				->andWhere('IFNULL(end_date, \'9999-99-99\')>=:actual_date')
				->params([':actual_date' => \Yii::$app->formatter->asDate(time(), 'y-MM-dd')])
				->one();
			if ($this->_poll)
				$this->setChartOptions();
		}
	}

	public function run() {
		return $this->render('poll', [
			'poll' => $this->_poll,
			'chartOptions' => $this->_chartOptions,
			'colWidth' => $this->colWidth
		]);
	}

	private function setChartOptions() {
		$individualChartOptions = [];
		$defaultChartOptions = [
			'id' => 'poll-id',
			'visualization' => $this->chartType
		];
		$titleTextStyle = [
			'color' => '#2f2f2f',
			'fontName' => 'Open Sans',
			'fontSize' =>  18,
			'bold' => false
		];
		$textStyle = [
			'color' => '#2f2f2f',
			'fontName' => 'Open Sans',
			'fontSize' =>  14
		];
		switch ($this->chartType) {
			case 'BarChart':
				$dataArray = [[Yii::t('front', 'answer'), Yii::t('front', 'voices'), ['role' => 'style']]];
				$colorsArray = ['forestgreen', 'dodgerblue', 'firebrick', 'gold', 'maroon', 'orange', 'violet', 'silver', 'aquamarine', 'pink'];
				$i = 0;
				foreach ( $this->_poll->answers as $pollAnswer ) {
					$dataArray[] = [$pollAnswer->answer,  ($pollAnswer->voices ? $pollAnswer->voices : 0), $colorsArray[$i]];
					++$i;
				}
				$individualChartOptions = [
					'options' => [
						'title' => $this->_poll->question,
						'titleTextStyle' => $titleTextStyle,
						'hAxis' => [
							'minValue' => 0,
						],
						'vAxis' => [
							'textStyle' => $textStyle
						],
						'legend' => [
							'position' => 'none'
						]
					],
					'dataArray' => $dataArray,
					'responsive' => true,
				];
				break;
			case 'PieChart':
				$rows = [];
				foreach ( $this->_poll->answers as $pollAnswer ) {
					$rows[] = ['c' => [['v' => $pollAnswer->answer], ['v' => $pollAnswer->voices]]];
				}
				$individualChartOptions = [
					'options' => [
						'title' => $this->_poll->question,
						'titleTextStyle' => $titleTextStyle,
						'textStyle' => $textStyle,
						'width' => 500,
						'height' => 250
					],
					'data' => [
						'cols' => [
							[
								'id' => 'answers',
								'label' => 'Answers',
								'type' => 'string'
							],
							[
								'id' => 'voices',
								'label' => 'Voices',
								'type' => 'number'
							]
						],
						'rows' => $rows
					],
					'responsive' => true
				];
				break;
		}
		$this->_chartOptions = ArrayHelper::merge($defaultChartOptions, $individualChartOptions);
	}
}