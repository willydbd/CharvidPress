<?php

	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		session_start();
		header("REFRESH: 0; URL=../");
		die("You're being redirected");
	}

	if (!isset($_POST)) die();
	if (!isset($_GET)) die();
	include('db.php');

	extract($_GET);
	extract($_POST);

	switch($get_request) {
		case 'get-production-payload':
			$payload = array('production'=>get_production_stages(), 'clients'=>get_existing_clients());
			echo json_encode($payload);
			break;
		case 'submit-order':
			echo add_job($_POST);
			break;
		case 'get-pending-jobs':
			echo json_encode(get_pending_jobs());
			break;
		case 'get-completed-jobs':
			echo json_encode(get_completed_jobs());
			break;
		case 'generate-receipt':
			write_receipt_log($id);
			echo date('d/m/y (g:i A)');
			break;
		case 'raise-invoice':
			raise_invoice_log($id);
			echo date('Y-m-d (g:iA)');
			break;
		case 'pos-receipt':
			write_pos_log($id);
			echo date('d-m-y g:i A');
			break;
	}

?>
