<?php


class WPRescueTimeAjaxController
{
	
	public function ajax_init() {
		$actionRoute = (isset($_GET['action_route'])) ? $_GET['action_route'] : '';
	
		$routes = [
			'get-rescue-users' => 'getRescueUsers',
			'get-user-profile' => 'getUserProfile',
			'get-daily-rescue-stat' => 'getDailyRescueStat'
		];
		
		if(isset($routes[$actionRoute])) {
			$this->{$routes[$actionRoute]}();
			die();
		}
	}
	
	public function getRescueUsers() {
		
		$user_query = new WP_User_Query(array(
			'fields' => array(
				'ID',
				'display_name',
				'user_email'
			),
			'meta_key' => '_rescue_time_api_key', 
			'meta_value' => false, 
			'meta_compare' => '!='
		));
		
		$users = $user_query->get_results();
		
		$data = array(
			'users' => $users
		);
		$this->response($data);
	}
	
	public function getUserProfile() {
		$userId = intval($_GET['user_id']);
		$user = get_user_by('ID', $userId);
		$formattedData = [
			'id' => $user->ID,
			'display_name' => $user->display_name,
			'user_email' => $user->user_email,
			'user_registered' => $user->user_registered,
			'has_rescue_time' => !!get_user_meta($userId, '_rescue_time_api_key', true)
		];
		
		$this->response(['user' => $formattedData]);
	}


	public function getDailyRescueStat() {
		$userId = intval($_REQUEST['user_id']);
		$apiKey = get_user_meta($userId, '_rescue_time_api_key', true);
		$client = new WPRescueTime\RescueDashboard\Dashboard($apiKey);
		
		$begin_date =  new \DateTime('today');
		$end_date =  new \DateTime('today');
		$stat = $client->getSummery($begin_date, $end_date);
		$this->response(['stat' => $stat]);
	}
	
	public function response( $data, $status = 200) {
		wp_send_json($data, $status);
		die();
	}
}

add_action('init', function() {
	if(!isset($_GET['do_debug'])) {
		return;
	}
	return;
	$apiKey = get_user_meta(1, '_rescue_time_api_key', true);
	$client = new WPRescueTime\RescueDashboard\Dashboard($apiKey);
	var_dump($client->getSummery());
	die();
});