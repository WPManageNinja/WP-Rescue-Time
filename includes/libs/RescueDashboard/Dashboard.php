<?php
namespace WPRescueTime\RescueDashboard;

use RescueTime\RequestQueryParameters as Params;
use RescueTime\Client;

class Dashboard
{
	public $Client;
	private $RescueTimeHelper;
	
	public function __construct($apiKey) {
		$this->Client = new Client($apiKey);
		$this->RescueTimeHelper = new DashboardHelper();
	}
	
	public function getSummery($startDate, $endDate) {
		$activities = $this->Client->getActivities( new Params([
				"perspective" => "interval",
				"resolution_time" => "day",
				"restrict_begin" => $startDate,
				"restrict_end" => $endDate,
				"restrict_kind" => "category"
			])
		);
		$timeByCategory = $this->RescueTimeHelper->calculateTimeByCategory($activities);
		$datapoints = array();
		foreach ($timeByCategory as $category => $seconds) {
			$datapoints[] = array(
				'title' => substr($category, 0, 30),
				'value' => round($seconds / 60 / 60, 2)
			);
		}
		return $datapoints;
		
		$graph = array(
			'graph' => array(
				'title' => 'Top Categories',
				'total' => true,
				'type' => 'bar',
				'yAxis' => array(
					'units' => array('suffix' => 'h')
				),
				'xAxis' => array('showEveryLabel' => true),
				'datasequences' => array(
					array(
						'title' => 'RescueTime',
						'datapoints' => $datapoints
					)
				)
			)
		);
		return $graph;
	}
	
}