<?php

	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		session_start();
		header("REFRESH: 0; URL=../");
		die("You're being redirected");
	}

	if (!isset($_POST)) die();
	if (!isset($_GET)) die();
	require_once('db.php');

	extract($_GET);
	extract($_POST);

	switch($get_request) {
		case 'get-log-titles':
			$titles = $LOG_TITLES;
			$staff  = get_all_staff(false);
			foreach ($staff as $s) {
				extract($s);
				$titles[] = array('title'=>'Staff log - '.$staff_id, 'label'=>$staff_id, 'tooltip'=>"$staff_name ($staff_role)");
			}
			echo json_encode($titles);
			break;
		case 'get-log-data':
			echo json_encode(get_log_data($label));
			break;
	}

?>
