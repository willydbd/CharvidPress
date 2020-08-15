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
		case 'get-inventory-categories':
			echo json_encode(get_inventory_categories());
			break;
		case 'get-inventory-items':
			echo json_encode(get_inventory_items());
			break;
	}

?>
