<?php

###################SETUP#################################################################
	if (session_id() == "") session_start();
	include('mysql-conn.php');
	include("include-globals.php");
	include("include-linkbuilder.php");
	include("include-permissions.php");

	$logged = isset($_SESSION['logged']) && $_SESSION['logged'] != '';
	$home_url = 'http://'.$_SERVER['HTTP_HOST'].'/CharvidPress/';
#########################################################################################

	/*
	 * Adds refund into the `refund` table
	 * Return type: void
	 */
	function accept_refund($data) {
		global $db;
		foreach ($data as $k=>$v) $data[$k] = $db->real_escape_string($v);
		extract($data);

		$db->query("INSERT INTO `refund` (`amount`,`staff`,`purpose`,`add_date`) VALUES ('$amount', '$staff', '$purpose', NOW())");

		#!-Log
		$log = sprintf("%s (%s) accepted refund of ₦%s from %s\nPurpose: %s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$amount,
						$staff,
						$purpose);
		write_log($_SESSION['logged']['staff_id'], LOG_ACCEPT_REFUND, $log);
		#!-------------------------------------------------
	}

	/*
	 * Gets existing clients in the system
	 * Return type: assoc()
	 */
	function get_existing_clients() {
		global $db;

		$r = $db->query("SELECT DISTINCT `client_name`, `client_address`, `client_phone` FROM `jobs` ORDER BY `client_name`");
		if (!$r || $r->num_rows == 0) return array();

		$res = array();
		while ($arr = $r->fetch_assoc())
			$res[] = array('name'=>$arr['client_name'], 'add'=>$arr['client_address'], 'phone'=>$arr['client_phone']);

		return $res;
	}

	/*
	 * Cancels specified job order
	 * Return type: void
	 */
	function cancel_job_order($id) {
		global $db;
		$id = $db->real_escape_string($id);

		$db->query("UPDATE `jobs` SET `canceled` = TRUE, `cancelation_date` = NOW() WHERE `job_id` = '$id'");
		$r = $db->query("SELECT `job_description` FROM `jobs` WHERE `job_id` = '$id'");
		$r = $r->fetch_object()->job_description;

		#!-Log
		$log = sprintf("%s (%s) canceled job with Order number: %s [%s]",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$id,
						$r);
		write_log($_SESSION['logged']['staff_id'], LOG_JOB_ORDER_CANCELLED, $log);
		#!-------------------------------------------------
	}

	/*
	 * Gets revenue overview over the months
	 * Return type: assoc()
	 */
	function get_revenue_overview() {
		global $db;

		$overview = array();
		$jobs = get_completed_jobs();
		$jobs = array_merge($jobs, get_pending_jobs());

		#!- `timesig` is for sorting by months
		foreach ($jobs as $j) {
			extract($j);
			$d = date('F, Y', strtotime($raw_completion_date));
			if (!isset($overview[$d])) $overview[$d] = array('revenue'=>0, 'vat'=>0, 'refund'=>0, 'timesig'=>strtotime($raw_completion_date));
			$overview[$d]['revenue'] += $job_estimate;
		}

		foreach ($overview as $d=>$val) {
			$overview[$d]['vat'] = .05 * $overview[$d]['revenue'];
			$overview[$d]['revenue'] -= $overview[$d]['vat'];
		}

		$r = $db->query("SELECT `amount`, `add_date` FROM `refund`");
		if (!$r || $r->num_rows == 0);
		else {
			while ($r1 = $r->fetch_assoc()) {
				extract($r1);
				$d = date('F, Y', strtotime($add_date));
				if (!isset($overview[$d])) $overview[$d] = array('revenue'=>0, 'vat'=>0, 'refund'=>0, 'timesig'=>strtotime($add_date));
				$overview[$d]['refund'] += $amount;
			}
		}

		#!- Mechanism for sorting multidimensional array by content arrays' key
		$sortArray = array();
		foreach($overview as $view){
			foreach($view as $key=>$value){
				if(!isset($sortArray[$key])){
					$sortArray[$key] = array();
				}
				$sortArray[$key][] = $value;
			}
		}
		$order_by = 'timesig';

		array_multisort($sortArray[$order_by],SORT_DESC,$overview);
		return $overview;
	}

	/*
	 * Flushes out results gotten from running a multi-query on db
	 * Return type: void
	 */
	function flush_multiquery() {
		global $db;
		while ($db->more_results()) {
			$db->next_result();
			$db->use_result();
		}
	}

	/*
	 * Checks if specified staff is permanent staff
	 * Return type: boolean
	 */
	function is_permanent_staff($staff_id) {
		global $db;
		$staff_id = $db->real_escape_string($staff_id);

		$r = $db->query("SELECT '1' FROM `staff` WHERE `staff_id` = '$staff_id' AND `is_permanent`");
		if (!$r || $r->num_rows==0) return false;
		return true;
	}

	/*
	 * Deletes staff identified by specified id
	 * Return type: void
	 */
	function delete_staff($staff_id) {
		global $db;
		$staff = $db->query("SELECT * FROM `staff` WHERE `staff_id` = '$staff_id'")->fetch_assoc();
		if ($staff['is_permanent']) return; #permanent staff cannot be deleted

		$staff_id = $db->real_escape_string($staff_id);

		$db->query("DELETE FROM `job_roles` WHERE `staff_id` = '$staff_id'");
		$db->query("DELETE FROM `staff` WHERE `staff_id` = '$staff_id'");
		$db->query("UPDATE `log` SET `actor` = '".LOG_ACTOR_DELETED_USER."' WHERE `actor` = '$staff_id'");

		#!-Log
		$log = sprintf("%s (%s) deleted staff '%s' with username '%s'",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$staff['staff_name'],
						$staff['staff_id']);
		write_log($_SESSION['logged']['staff_id'], LOG_STAFF_DELETED, $log);
		#!-------------------------------------------------

		$db->query("UPDATE `log` SET `actor` = '".LOG_ACTOR_DELETED_USER."' WHERE `actor` = '$staff_id'");
	}

	/*
	 * Fetches log data based on specified label.
	 * If label is a defined constant, it fetches based on action
	 * Else, fetch is actor-based
	 * Return type: assoc()
	 */
	function get_log_data($label) {
		global $db;
		$token = defined($label)? 'action':'actor';
		$label = $db->real_escape_string($label);
		$where_clause = '';

		if (defined($label)) {
			switch ($label) {
				case LOG_CATEGORY_WORKFORCE:
					$where_clause = sprintf("`$token` = '%s' OR `$token` = '%s' OR `$token` = '%s'",
											LOG_NEW_STAFF, LOG_STAFF_INFO_UPDATED, LOG_STAFF_DELETED);
					break;
				case LOG_CATEGORY_PRODUCTION:
					$where_clause = sprintf("`$token` = '%s' OR `$token` = '%s' OR `$token` = '%s'",
											LOG_NEW_PRODUCTION_STAGE, LOG_PRODUCTION_STAGE_UPDATED, LOG_PRODUCTION_STAGE_DELETED);
					break;
				case LOG_CATEGORY_INVENTORY:
					$where_clause = sprintf("`$token` = '%s' OR `$token` = '%s' OR `$token` = '%s'",
											LOG_ADD_INVENTORY_ITEM, LOG_INVENTORY_ITEM_UPDATED, LOG_INVENTORY_ITEM_DELETED);
					break;
				case LOG_CATEGORY_JOBS:
					$where_clause = sprintf("`$token` = '%s' OR `$token` = '%s' OR `$token` = '%s' OR `$token` = '%s' OR `$token` = '%s'",
											LOG_NEW_JOB, LOG_NEW_JOB_INSTALMENT, LOG_INVOICE_RAISED, LOG_RECEIPT_GENERATED, LOG_JOB_ORDER_CANCELLED);
					break;
				case LOG_SYSTEM_ENTRY_EXIT:
					$where_clause = sprintf("`$token` = '%s'", $label);
					break;
				case LOG_CATEGORY_ARCHIVED:
					$where_clause = sprintf("`actor` = '%s'", LOG_ACTOR_DELETED_USER);
					break;
				case LOG_ACCEPT_REFUND:
					$where_clause = sprintf("`$token` = '%s'", LOG_ACCEPT_REFUND);
					break;
			}
		} else $where_clause = sprintf("`$token` = '%s'", $label);

		$r = $db->query("SELECT DISTINCT DATE(`when`) AS `day` FROM `log` WHERE $where_clause ORDER BY `day` DESC");
		if (!$r || $r->num_rows==0) return array();

		$res = array();
		while ($r1 = $r->fetch_assoc()) {
			extract($r1);
			$day_log = array( 'day'=>date('D jS F Y', strtotime($day) ), 'content'=>array() );

			$r1 = $db->query("SELECT * FROM `log` WHERE ($where_clause) AND DATE(`when`) = '$day' ORDER BY `id` DESC");
			if (!$r || $r->num_rows==0);
			else {
				while ($r2 = $r1->fetch_assoc()) {
					extract($r2);
					$r2['when'] = date('g:iA', strtotime($when));
					$day_log['content'][] = $r2;
				}
			}

			$res[] = $day_log;
		}

		return $res;
	}

	/*
	 * Takes log whenever a pos receipt is generated for a job
	 * Return type: void
	 */
	function write_pos_log($job_id) {
		#!-Log
		$log = sprintf("%s (%s) generated POS receipt for Order: %s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$job_id);
		write_log($_SESSION['logged']['staff_id'], LOG_RECEIPT_GENERATED, $log);
		#!-------------------------------------------------
	}

	/*
	 * Takes log whenever an invoice is raised for a job
	 * Return type: void
	 */
	function raise_invoice_log($job_id) {
		#!-Log
		$log = sprintf("%s (%s) raised an invoice for Order: %s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$job_id);
		write_log($_SESSION['logged']['staff_id'], LOG_INVOICE_RAISED, $log);
		#!-------------------------------------------------
	}

	/*
	 * Takes log whenever a receipt is generated for a job
	 * Return type: void
	 */
	function write_receipt_log($job_id) {
		#!-Log
		$log = sprintf("%s (%s) generated receipt for Order: %s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$job_id);
		write_log($_SESSION['logged']['staff_id'], LOG_RECEIPT_GENERATED, $log);
		#!-------------------------------------------------
	}

	/*
	 * Adds new instalment of payment to specified job's reimbursement
	 * Return type: void
	 */
	function add_new_instalment($data) {
		global $db;
		if (!is_pending_job($data['job_id'])) return;
		foreach ($data as $k=>$v) $data[$k] = $db->real_escape_string($v);
		extract($data);

		$r = $db->query("UPDATE `jobs` SET `reimbursement` = `reimbursement`+$new_instalment, `last_instalment` = '$new_instalment' WHERE NOT `canceled` AND `job_id` = '$job_id'");
		if (!is_pending_job($data['job_id'])) $db->query("UPDATE `jobs` SET `completion_date` = NOW() WHERE NOT `canceled` AND `job_id` = '$job_id'");

		#!-Log
		$log = sprintf("%s (%s) received new instalment of N%s for Order: %s [%s]".(!is_pending_job($data['job_id'])? ".\nPayment is now complete":''),
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$new_instalment,
						$job_id,
						$job_desc);
		write_log($_SESSION['logged']['staff_id'], LOG_NEW_JOB_INSTALMENT, $log);
		#!-------------------------------------------------
	}

	/*
	 * Checks if specified job is pending or not
	 * Return type: boolean
	 */
	function is_pending_job($job_id) {
		global $db;
		$job_id = $db->real_escape_string($job_id);

		$r = $db->query("SELECT '1' FROM `jobs` WHERE NOT `canceled` AND `job_id` = '$job_id' AND (`job_estimate` - `discount`) > `reimbursement`");
		if (!$r || $r->num_rows == 0) return false;
		return true;
	}

	/*
	 * Gets all completed jobs: those for which payment has been made in full
	 * Return type: assoc
	 */
	function get_completed_jobs() {
		global $db;

		$r = $db->query("SELECT * FROM `jobs` WHERE NOT `canceled` AND (`job_estimate` - `discount`) <= `reimbursement`");
		if (!$r || $r->num_rows == 0) return array();

		$res = array();
		while ($r1 = $r->fetch_assoc()) {
			$r1['order_date'] = date('Y-m-d (g:iA)', strtotime($r1['order_date']));
			$r1['raw_completion_date'] = $r1['completion_date'];
			$r1['completion_date'] = date('Y-m-d (g:iA)', strtotime($r1['completion_date']));
			$res[] = $r1;
		}
		return $res;
	}

	/*
	 * Gets all pending jobs: those for which payment is yet to be made in full
	 * Return type: assoc
	 */
	function get_pending_jobs() {
		global $db;

		$r = $db->query("SELECT * FROM `jobs` WHERE NOT `canceled` AND (`job_estimate` - `discount`) > `reimbursement`");
		if (!$r || $r->num_rows == 0) return array();

		$res = array();
		while ($r1 = $r->fetch_assoc()) {
			$r1['order_date'] = date('Y-m-d (g:iA)', strtotime($r1['order_date']));
			$r1['completion_date'] = date('Y-m-d (g:iA)', strtotime($r1['completion_date']));
			$res[] = $r1;
		}
		return $res;
	}

	/*
	 * Adds new job to jobs, returns job_id and order_date on successful add
	 * Return type: string
	 */
	function add_job($data) {
		global $db;

		#!-Backup incoming data
		$data_copy = $data;

		#!-Ensure unique job_id
		while (job_exists($data['job_id'])) $data['job_id'] = new_job_id();

		#!-Escape incoming data and build INSERT query
		$cols = $vals = '';
		foreach ($data as $k=>$v) {
			$data[$k] = $db->real_escape_string($v);
			$cols .= "`$k`,";
			$vals .= "'".$data[$k]."',";
		}
		$cols = substr($cols,0,-1).',`staff_in_charge`,`order_date`'; $vals = substr($vals,0,-1).",'".$_SESSION['logged']['staff_name'].' ('.$_SESSION['logged']['staff_id'].')'."',NOW()";
		$sql = "INSERT INTO `jobs` ($cols) VALUES ($vals)";

		$db->query($sql);
		if (!is_pending_job($data['job_id'])) $db->query("UPDATE `jobs` SET `completion_date` = NOW() WHERE NOT `canceled` AND `job_id` = '".$data['job_id']."'");

		#!-Log
		$log = sprintf("%s (%s) took a new job order with ID '%s' from %s [%s]",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$data['job_id'],
						$data_copy['client_name'],
						$data_copy['job_description']);
		write_log($_SESSION['logged']['staff_id'], LOG_NEW_JOB, $log);
		#!-------------------------------------------------

		if ($db->insert_id > 0) return $data['job_id'].'+'.date('Y-m-d (g:iA)');
	};

	/*
	 * Checks if a job with specified job_id exists
	 * Return type: boolean
	 */
	function job_exists($job_id) {
		global $db;
		$job_id = $db->real_escape_string($job_id);

		$r = $db->query("SELECT '1' FROM `jobs` WHERE `job_id` = '$job_id'");
		if (!$r || $r->num_rows == 0) return false;
		return true;
	};

	/*
	 * Generates a unique job id
	 * Return type: string
	 */
	function new_job_id() {
		global $db;

		/*$def = '0';
		$r = $db->query("SELECT `job_id` FROM `jobs` ORDER BY `id` DESC LIMIT 1");
		$txt = ($r->num_rows == 0)? $def:$r->fetch_object()->job_id;
		$txt = preg_match('/[0-9]+/',$txt)? $txt:$def;

		if ($txt == $def) return '1';
		$txt++;

		return $txt;*/

		$r = $db->query("SELECT COUNT('1') AS `job_count` FROM `jobs` WHERE DATE(`order_date`) = CURDATE()");
		$r = $r->fetch_object()->job_count;
		$date_part = date('dmy-');
		$r++;
		return $date_part.$r;
	}

	/*
	 * Fetches items that are already out of stock in the inventory
	 * Return type: assoc()
	 */
	function get_out_of_stock_items() {
		global $db;

		$r = $db->query("SELECT * FROM `inventory` WHERE `stock` = 0");
		if (!$r || $r->num_rows == 0) return array();

		$res = array();
		while ($r1 = $r->fetch_assoc()) $res[] = $r1;
		return $res;
	}

	/*
	 * Fetches items that are almost out of stock in the inventory
	 * Return type: assoc()
	 */
	function get_threshold_items() {
		global $db;

		$r = $db->query("SELECT * FROM `inventory` WHERE `stock` <= `threshold` AND `stock` != 0");
		if (!$r || $r->num_rows == 0) return array();

		$res = array();
		while ($r1 = $r->fetch_assoc()) $res[] = $r1;
		return $res;
	}

	/*
	 * Checks validity of session token and terminates script if invalid
	 * Return type: void
	 */
	function check_session_token() {
		global $db; global $home_url;
		$staff_id = $db->real_escape_string($_SESSION['logged']['staff_id']);
		$session_token = $db->real_escape_string($_SESSION['logged']['session_token']);

		$r = $db->query("SELECT '1' FROM `staff` WHERE `staff_id` = '$staff_id' AND `session_token` = '$session_token'");
		if (!$r || $r->num_rows == 0) {
			logout();
			echo "Session expired, please login again";
			echo "<meta http-equiv=\"refresh\" content=\"3;url=$home_url\" />";
			die();
		}
	};

	/*
	 * Updates session token for specified staff
	 * Return type: void
	 */
	function update_session_token($staff_id, $session_token = '') {
		global $db;
		$staff_id = $db->real_escape_string($staff_id);
		$session_token = ($session_token == '')? sha1(rand()):$session_token;
		$session_token = $db->real_escape_string($session_token);
		$db->query("UPDATE `staff` SET `session_token` = '$session_token' WHERE `staff_id` = '$staff_id'");
	};

	/*
	 * Removes inventory item identified by specified ID
	 * Return type: void
	 */
	function remove_inventory_item($id) {
		global $db;
		$item = get_inventory_item($id);
		$id = $db->real_escape_string($id);
		$db->query("DELETE FROM `inventory` WHERE `id` = '$id'");

		#!-Log
		$log = sprintf("%s (%s) deleted '%s' from inventory under '%s' category",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$item['item_name'],
						$item['item_cat']);
		write_log($_SESSION['logged']['staff_id'], LOG_INVENTORY_ITEM_DELETED, $log);
		#!-------------------------------------------------
	};

	/*
	 * Adds a new item to the inventory
	 * Return type: void
	 */
	function update_inventory_item($data) {
		global $db;
		$old_item = get_inventory_item($data['id']);
		$raw_data = $data;
		foreach ($data as $k=>$v) $data[$k] = $db->real_escape_string($v);
		extract($data);
		$db->query("UPDATE `inventory` SET `item_name` = '$item_name', `item_cat` = '$item_cat', `unit_price` = '$unit_price', `stock` = '$stock', `threshold` = '$threshold' WHERE `id` = '$id'");

		#!-Log
		$log = sprintf("%s (%s) updated '%s' in inventory under '%s' category.\n\nNew item name: %s\nNew category: %s\nInitial price: N%s\nNew price: N%s\nInitial stock: %s\nNew stock: %s\nInitial threshold: %s\nNew threshold: %s".($raw_data['purpose'] == ''? '':"\n\nReason for stock change: ".$raw_data['purpose']),
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$old_item['item_name'],
						$old_item['item_cat'],
						$item_name,
						$item_cat,
						$old_item['unit_price'],
						$unit_price,
						$old_item['stock'],
						$stock,
						$old_item['threshold'],
						$threshold);
		write_log($_SESSION['logged']['staff_id'], LOG_INVENTORY_ITEM_UPDATED, $log);
		#!-------------------------------------------------
	}

	/*
	 * Gets all item in the inventory, grouped by categories
	 * Return type: assoc()
	 */
	function get_inventory_items() {
		global $db;

		$cats = get_inventory_categories();
		$res = array();
		foreach ($cats as $cat) {
			$cat = $db->real_escape_string($cat);
			$r = $db->query("SELECT * FROM `inventory` WHERE `item_cat` = '$cat'");
			if (!$r || $r->num_rows == 0);
			else while ($r1 = $r->fetch_assoc()) $res[$cat][] = $r1;
		}
		return $res;
	}

	/*
	 * Gets item in the inventory identified by specified ID
	 * Return type: assoc()
	 */
	function get_inventory_item($id) {
		global $db;
		$id = $db->real_escape_string($id);

		$r = $db->query("SELECT * FROM `inventory` WHERE `id` = '$id'");
		if (!$r || $r->num_rows == 0) return assoc();
		return $r->fetch_assoc();
	}

	/*
	 * Adds a new item to the inventory
	 * Return type: void
	 */
	function add_inventory_item($data) {
		global $db;
		$data_copy = $data;
		foreach ($data as $k=>$v) $data[$k] = $db->real_escape_string($v);
		extract($data);
		$db->query("INSERT INTO `inventory` (`item_name`, `item_cat`, `unit_price`, `stock`, `threshold`, `add_date`)
					VALUES ('$item_name', '$item_cat', '$unit_price', '$stock', '$threshold', NOW())");

		extract($data_copy);

		#!-Log
		$log = sprintf("%s (%s) added '%s' to inventory under '%s' category.\n\nPrice: N%s\nStock: %s\nAlert threshold: %s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$item_name,
						$item_cat,
						$unit_price,
						$stock,
						$threshold);
		write_log($_SESSION['logged']['staff_id'], LOG_ADD_INVENTORY_ITEM, $log);
		#!-------------------------------------------------
	}

	/*
	 * Outputs a SweetAlert on pageload
	 * Return type: void
	 */
	function js_alert($msg, $type = ALERT_TYPE_NORMAL) {
		global $db;
		$msg = $db->real_escape_string($msg);
		echo '<script> '.
			"var alert_title = '".$db->real_escape_string("Report")."';".
			"var alert_text = '".$db->real_escape_string($msg)."';".
			"var alert_type = '$type';"
		.' </script>';
	};

	/*
	 * Checks if specified inventory item exists
	 * Return type: boolean
	 */
	function inventory_item_exists($item, $cat) {
		global $db;
		$item = $db->real_escape_string($item);
		$cat = $db->real_escape_string($cat);

		$r = $db->query("SELECT '1' FROM `inventory` WHERE `item_name` = '$item' AND `item_cat` = '$cat'");
		if (!$r || $r->num_rows == 0) return false;
		return true;
	};

	/*
	 * Gets all item categories in the inventory
	 * Return type: assoc()
	 */
	function get_inventory_categories() {
		global $db;

		$r = $db->query("SELECT DISTINCT `item_cat` FROM `inventory`");
		if (!$r || $r->num_rows == 0) return array();
		while ($r1 = $r->fetch_assoc()) $res[] = $r1['item_cat'];
		return $res;
	}

	/*
	 * Removes production stage identified by specified stage ID and all its associated processes
	 * Return type: void
	 */
	function remove_production_stage($stage_id) {
		global $db;
		$stage_name = get_production_stage_name($stage_id);
		$stage_id = $db->real_escape_string($stage_id);

		$db->query("DELETE FROM `production_processes` WHERE `production_stage` = '$stage_id'");
		$proc_count = $db->affected_rows;
		$db->query("DELETE FROM `production_stages` WHERE `id` = '$stage_id'");

		#!-Log
		$log = sprintf("%s (%s) deleted '%s' production stage with %d processes.",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$stage_name,
						$proc_count);
		write_log($_SESSION['logged']['staff_id'], LOG_PRODUCTION_STAGE_DELETED, $log);
		#!-------------------------------------------------
	};

	/*
	 * Gets name of production stage identified by specified stage ID
	 * Return type: string
	 */
	function get_production_stage_name($stage_id) {
		global $db;
		$stage_id = $db->real_escape_string($stage_id);
		$r = $db->query("SELECT `stage_name` FROM `production_stages` WHERE `id` = '$stage_id'");
		if (!$r || $r->num_rows==0) return '';
		return $r->fetch_object()->stage_name;
	}

	/*
	 * Updates details of production stage identified by specified ID
	 * Return type: void
	 */
	function update_production_stage($data) {
		global $db;

		$data_copy = $data;
		$initial_processes = get_production_processes($data['stage_id']);
		$orig_stage_name = get_production_stage_name($data['stage_id']);

		foreach ($data as $k=>$v) if (!is_array($v)) $data[$k] = $db->real_escape_string($v);
		extract($data);
		$db->query("UPDATE `production_stages` SET `stage_name` = '$stage_name', `stage_rank` = '$stage_rank' WHERE `id` = '$stage_id'");
		#$db->query("DELETE FROM `production_processes` WHERE `production_stage` = '$stage_id'");

		$procs = $existing = array();
		foreach ($proc_ids as $k=>$v) {
			$procs[] = array('id'=>$proc_ids[$k], 'process_name'=>$proc_names[$k], 'process_rate'=>$proc_rates[$k], 'process_unit'=>$proc_units[$k]);
			if ($proc_ids[$k] != '') $existing[$proc_ids[$k]] = '';
		}

		$insert = $update = $delete = $delete_log = $change_log = $add_log = '';
		foreach($procs as $proc) {
			if (!isset($initial_processes[$proc['id']])) {
				$insert = $insert == ''? "INSERT INTO `production_processes` (`production_stage`,`process_name`,`process_rate`,`process_unit`,`add_date`) VALUES ":$insert;

				$insert .= sprintf("('$stage_id','%s','%s','%s',NOW()),",
							$db->real_escape_string($proc['process_name']),
							$db->real_escape_string($proc['process_rate']),
							$db->real_escape_string($proc['process_unit']));
				$add_log .= sprintf("New process: %s (₦%s per %s)\n",
							$proc['process_name'],
							$proc['process_rate'],
							$proc['process_unit']);
			}
			else {
				$ip = $initial_processes[$proc['id']];
				if ($proc != $ip) {
					$update .= sprintf("UPDATE `production_processes` SET `process_name` = '%s', `process_rate` = '%s', `process_unit` = '%s' WHERE `id` = '%s';",
							$db->real_escape_string($proc['process_name']),
							$db->real_escape_string($proc['process_rate']),
							$db->real_escape_string($proc['process_unit']),
							$db->real_escape_string($proc['id']));

					$ch = '';
					if ($proc['process_name'] != $ip['process_name']) #name changed
						$ch .= "'".$ip['process_name']."' to '".$proc['process_name']."',";
					else $ch .= $ip['process_name']; #name unchanged

					if ($proc['process_rate'] != $ip['process_rate'] || $proc['process_unit'] != $ip['process_unit'])  #others changed
						$ch .= " from '₦".$ip['process_rate']." per ".$ip['process_unit']."' to '₦".$proc['process_rate']." per ".$proc['process_unit']."',";

					$ch = substr($ch,0,-1);
					$change_log .= "Changed process: $ch\n";
				}
			}
		}
		$insert = substr($insert,0,-1);

		foreach ($initial_processes as $ip) {
			if (!isset($existing[$ip['id']])) {
				$delete = $delete == ''? "DELETE FROM `production_processes` WHERE ":$delete;
				$delete .= sprintf("`id` = '%s' OR ",
							$ip['id']);

				$delete_log .= sprintf("Deleted process: %s (₦%s per %s)\n",
							$ip['process_name'],
							$ip['process_rate'],
							$ip['process_unit']);
			}
		}
		$delete = substr($delete,0,-4);

		#!-DB operations
		if ($delete != '') $db->query($delete);
		flush_multiquery();
		if ($insert != '') $db->query($insert);
		if ($update != '') $db->multi_query($update);
		flush_multiquery();

		#!-Log
		if ($add_log != '' || $change_log != '' || $delete_log != '') {
			$log = $add_log.($change_log != ''? "\n-----------------------------\n":'').$change_log.($delete_log != ''? "\n-----------------------------\n":'').$delete_log;
			$log = sprintf("%s (%s) updated '%s' production stage.\n".(($stage_name != $orig_stage_name)? "Renamed from '$orig_stage_name' to '".$data_copy['stage_name']."'\n":'').$log,
							$_SESSION['logged']['staff_name'],
							$_SESSION['logged']['staff_role'],
							$orig_stage_name);
			write_log($_SESSION['logged']['staff_id'], LOG_PRODUCTION_STAGE_UPDATED, $log);
		} else {
			if ($stage_name != $orig_stage_name) {
				$log = sprintf("%s (%s) updated '%s' production stage.\nRenamed from '$orig_stage_name' to '".$data_copy['stage_name']."'\n",
							$_SESSION['logged']['staff_name'],
							$_SESSION['logged']['staff_role'],
							$orig_stage_name);
				write_log($_SESSION['logged']['staff_id'], LOG_PRODUCTION_STAGE_UPDATED, $log);
			}
		}
		#!-------------------------------------------------
	}

	/*
	 * Gets details of all production stages
	 * Return type: assoc()
	 */
	function get_production_processes($stage_id) {
		global $db;
		$stage_id = $db->real_escape_string($stage_id);
		$r = $db->query("SELECT `id`,`process_name`,`process_rate`,`process_unit` FROM `production_processes` WHERE `production_stage` = '$stage_id'");
		if (!$r || $r->num_rows==0) return array();
		$res = array();
		while ($r1 = $r->fetch_assoc())
			$res[$r1['id']] = $r1;
		return $res;
	}

	/*
	 * Gets details of all production stages
	 * Return type: assoc()
	 */
	function get_production_stages() {
		global $db;
		$r = $db->query("SELECT * FROM `production_stages` ORDER BY `stage_rank`, `add_date` DESC");
		if (!$r || $r->num_rows == 0) return array();
		$res = array();
		while ($r1 = $r->fetch_assoc()) {
			$r1['processes'] = array();
			$r2 = $db->query("SELECT * FROM `production_processes` WHERE `production_stage` = '".$db->real_escape_string($r1['id'])."'");
			if (!$r2 || $r2->num_rows == 0);
			else {
				while ($r3 = $r2->fetch_assoc()) $r1['processes'][] = $r3;
			}
			$res[] = $r1;
		}
		return $res;
	};

	/*
	 * Adds a new production stage to list of production stages
	 * Return type: void
	 */
	function add_production_stage($data) {
		global $db;
		$data_copy = $data;
		foreach ($data as $k=>$v) $data[$k] = $db->real_escape_string($v);
		extract($data);
		$db->query("INSERT INTO `production_stages` (`stage_name`, `stage_rank`, `add_date`) VALUES ('$stage_name', '$stage_rank', NOW())");

		#!-Log
		$log = sprintf("%s (%s) added new production stage: '%s' Rank #%s",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_role'],
						$data_copy['stage_name'],
						$data_copy['stage_rank']);
		write_log($_SESSION['logged']['staff_id'], LOG_NEW_PRODUCTION_STAGE, $log);
		#!-------------------------------------------------
	}

	/*
	 * Checks if specified production stage exists
	 * Return type: boolean
	 */
	function production_stage_exists($stage) {
		global $db;
		$stage = $db->real_escape_string($stage);
		$r = $db->query("SELECT '1' FROM `production_stages` WHERE `stage_name` = '$stage'");
		if (!$r || $r->num_rows == 0) return false;
		return true;
	};

	/*
	 * Gets details of all registered staff
	 * include_roles: determines whether to fetch job_roles assigned to the staff
	 * Return type: assoc()
	 */
	function get_all_staff($include_roles = true) {
		global $db;
		$r = $db->query("SELECT * FROM `staff`");
		if (!$r || $r->num_rows == 0) return array();
		$res = array();
		while ($r1 = $r->fetch_assoc()) {
			if ($include_roles) {
				$r2 = $db->query("SELECT * FROM `job_roles` WHERE `staff_id` = '".$db->real_escape_string($r1['staff_id'])."'");
				if (!$r2 || $r2->num_rows == 0);
				else {
					$roles = array();
					$r2 = $r2->fetch_assoc();
					if ($r2['take_job_order']) $roles[] = 'Take job orders';
					if ($r2['view_job_orders']) $roles[] = 'View job orders';
					if ($r2['update_pending_jobs']) $roles[] = 'Update pending jobs';
					if ($r2['manage_staff']) $roles[] = 'Manage staff';
					if ($r2['manage_production']) $roles[] = 'Oversee production';
					if ($r2['manage_inventory']) $roles[] = 'Manage inventory';
					if ($r2['inventory_notification']) $roles[] = 'Get inventory notifications';
					if ($r2['inventory_overview']) $roles[] = 'Access inventory overview';
					if ($r2['view_system_log']) $roles[] = 'View system log';
					if ($r2['view_revenue_overview']) $roles[] = 'View revenue overview';
					if ($r2['cancel_job_orders']) $roles[] = 'Cancel job orders';
					if ($r2['accept_refund']) $roles[] = 'Accept refund';
					$r1['roles'] = $roles;
				}
			}
			$res[] = $r1;
		}
		return $res;
	};

	/*
	 * Adds a new member of staff to staff list when the specified ID does not exist
	 * Else, updates existing data
	 * Return type: void
	 */
	function add_staff($data) {
		global $db;
		$data_copy = $data;
		if (!staff_exists($data['staff_id'])) {
			$cols = $vals = '';
			foreach ($data as $k=>$v) {
				if ($k != 'perms' && $k != 'add_staff_btn') {
					$data[$k] = $db->real_escape_string($v);
					$cols .= "`$k`,";
					$vals .= "'".$data[$k]."',";
				}
			}
			$cols = substr($cols,0,-1).',`add_date`'; $vals = substr($vals,0,-1).',NOW()';
			$sql = "INSERT INTO `staff` ($cols) VALUES ($vals)";
			$db->query($sql);

			$cols = $vals = $log_perms = '';
			foreach($data['perms'] as $p=>$v) {
				$cols .= "`$p`,";
				$vals .= "TRUE,";
				$log_perms .= "'$p', ";
			}

			$cols = substr($cols,0,-1).',`staff_id`';
			$vals = substr($vals,0,-1).",'".$data['staff_id']."'";
			$log_perms = substr($log_perms,0,-2);

			$sql = "INSERT INTO `job_roles` ($cols) VALUES ($vals)";
			$db->query($sql);

			#!-Log
			$log = sprintf("%s (%s) added new staff: %s (%s) as '%s'.\nPermissions: %s",
							$_SESSION['logged']['staff_name'],
							$_SESSION['logged']['staff_role'],
							$data_copy['staff_name'],
							$data_copy['staff_id'],
							$data_copy['staff_role'],
							$log_perms);
			write_log($_SESSION['logged']['staff_id'], LOG_NEW_STAFF, $log);
			#!-------------------------------------------------
		} else {
			$set = '';
			foreach ($data as $k=>$v) {
				if ($k != 'perms' && $k != 'update_staff_btn' && $k != 'staff_id') {
					$data[$k] = $db->real_escape_string($v);
					$set .= "`$k` = '".$data[$k]."',";
				} elseif ($k == 'staff_id') $data[$k] = $db->real_escape_string($v);
			}
			$set = substr($set,0,-1);
			$sql = "UPDATE `staff` SET $set WHERE `staff_id` = '".$data['staff_id']."'";
			$db->query($sql);

			$db->query("UPDATE `job_roles` SET `take_job_order` = FALSE, `manage_staff` = FALSE, `manage_production` = FALSE,
				`manage_inventory` = FALSE, `inventory_notification` = FALSE, `inventory_overview` = FALSE,
				`view_job_orders` = FALSE, `update_pending_jobs` = FALSE, `view_system_log` = FALSE,
				`view_revenue_overview` = FALSE, `cancel_job_orders` = FALSE, `accept_refund` = FALSE
				WHERE `staff_id` = '".$data['staff_id']."'");
			$set = '';
			$log_perms = '';
			foreach($data['perms'] as $p=>$v) {
				$set .= "`$p` = TRUE,";
				$log_perms .= "'$p', ";
			}
			$set = substr($set,0,-1); $log_perms = substr($log_perms,0,-2);

			$sql = "UPDATE `job_roles` SET $set WHERE `staff_id` = '".$data['staff_id']."'";
			$db->query($sql);

			if (is_permanent_staff($data_copy['staff_id'])) $db->query("UPDATE `job_roles` SET `manage_staff` = TRUE WHERE `staff_id` = '".$data['staff_id']."'");

			#!-Log
			$log = sprintf("%s (%s) updated staff details for: %s.\nUpdated staff now has the following permissions: %s",
							$_SESSION['logged']['staff_name'],
							$_SESSION['logged']['staff_role'],
							$data_copy['staff_id'],
							$log_perms);
			write_log($_SESSION['logged']['staff_id'], LOG_STAFF_INFO_UPDATED, $log);
			#!-------------------------------------------------

			update_session_token($data['staff_id']);
		}
	};

	/*
	 * Checks if a staff with specified ID exists
	 * Return type: boolean
	 */
	function staff_exists($id) {
		global $db;
		$id = $db->real_escape_string($id);
		$r = $db->query("SELECT '1' FROM `staff` WHERE `staff_id` = '$id'");
		if (!$r || $r->num_rows == 0) return false;
		return true;
	};

	/*
	 * Authenticates user and sets necessary session values
	 * Accepts: assoc()
	 * Returns ->
	 * 0: success
	 * 1: some problem
	 * 2: unregistered username
	 * 3: incorrect password
	 */
	function login($data) {
		global $db;
		extract($data);
		$username = $db->real_escape_string($username);
		$r = $db->query("SELECT * FROM `staff` WHERE `staff_id` = '$username'");

		if (!$r) {
			#!-Log
			$log = sprintf("Login failed for '%s', unknown cause",
							$username);
			write_log($username, LOG_SYSTEM_ENTRY_EXIT, $log);
			#!-------------------------------------------------

			return 1;
		}

		if ($r->num_rows == 0) {
			#!-Log
			$log = sprintf("Invalid login username '%s'",
							$username);
			write_log('', LOG_SYSTEM_ENTRY_EXIT, $log);
			#!-------------------------------------------------

			return 2;
		}

		$r = $r->fetch_assoc();
		if ($pswd != $r['staff_pswd']) {
			#!-Log
			$log = sprintf("Invalid password for '%s'",
							$username);
			write_log('', LOG_SYSTEM_ENTRY_EXIT, $log);
			#!-------------------------------------------------

			return 3;
		}

		$staff_data = $r;
		$r = $db->query("SELECT * FROM `job_roles` WHERE `staff_id` = '$username'");
		if (!$r) return 1;
		$staff_data['roles'] = $r->fetch_assoc();

		$session_token = sha1(rand());
		update_session_token($staff_data['staff_id'], $session_token);
		$staff_data['session_token'] = $session_token;

		$_SESSION['logged'] = $staff_data;

		#!-Log
		$log = sprintf("%s (%s) - %s - logged in",
						$staff_data['staff_name'],
						$username,
						$staff_data['staff_role']);
		write_log($username, LOG_SYSTEM_ENTRY_EXIT, $log);
		#!-------------------------------------------------

		return 0;
	}

	/*
	 * Writes supplied information into log
	 * Accepts: string
	 * Return type: void
	 */
	function write_log($actor, $action, $report) {
		 global $db;
		 $actor = $db->real_escape_string($actor);
		 $action = $db->real_escape_string($action);
		 $report = $db->real_escape_string($report);

		 $db->query("INSERT INTO `log` (`actor`,`action`,`report`,`when`) VALUES ('$actor', '$action', '$report', NOW())");
	}

	/*
	 * Unsets current session to log out active staff
	 * Return type: void
	 */
	function logout() {
		#!-Log
		$log = sprintf("%s (%s) - %s - logged out",
						$_SESSION['logged']['staff_name'],
						$_SESSION['logged']['staff_id'],
						$_SESSION['logged']['staff_role']);
		write_log($_SESSION['logged']['staff_id'], LOG_SYSTEM_ENTRY_EXIT, $log);
		#!-------------------------------------------------

		session_unset();
	}

?>
