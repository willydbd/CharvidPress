<?php

	#!-Sweet Alert
	define("ALERT_TYPE_FAILED",'error');
	define("ALERT_TYPE_NORMAL",'info');
	define("ALERT_TYPE_SUCCESS",'success');

	#!-System Log: Actors
	define('LOG_ACTOR_DELETED_USER','LOG_ACTOR_DELETED_USER');

	#!-System Log: Actions
	define('LOG_SYSTEM_ENTRY_EXIT','LOG_SYSTEM_ENTRY_EXIT');

	define('LOG_CATEGORY_WORKFORCE','LOG_CATEGORY_WORKFORCE');
	define('LOG_NEW_STAFF','LOG_NEW_STAFF');
	define('LOG_STAFF_INFO_UPDATED','LOG_STAFF_INFO_UPDATED');
	define('LOG_STAFF_DELETED','LOG_STAFF_DELETED');

	define('LOG_CATEGORY_PRODUCTION','LOG_CATEGORY_PRODUCTION');
	define('LOG_NEW_PRODUCTION_STAGE','LOG_NEW_PRODUCTION_STAGE');
	define('LOG_PRODUCTION_STAGE_UPDATED','LOG_PRODUCTION_STAGE_UPDATED');
	define('LOG_PRODUCTION_STAGE_DELETED','LOG_PRODUCTION_STAGE_DELETED');

	define('LOG_CATEGORY_INVENTORY','LOG_CATEGORY_INVENTORY');
	define('LOG_ADD_INVENTORY_ITEM','LOG_ADD_INVENTORY_ITEM');
	define('LOG_INVENTORY_ITEM_UPDATED','LOG_INVENTORY_ITEM_UPDATED');
	define('LOG_INVENTORY_ITEM_DELETED','LOG_INVENTORY_ITEM_DELETED');

	define('LOG_CATEGORY_JOBS','LOG_CATEGORY_JOBS');
	define('LOG_NEW_JOB','LOG_NEW_JOB');
	define('LOG_NEW_JOB_INSTALMENT','LOG_NEW_JOB_INSTALMENT');
	define('LOG_INVOICE_RAISED','LOG_INVOICE_RAISED');
	define('LOG_RECEIPT_GENERATED','LOG_RECEIPT_GENERATED');
	define('LOG_JOB_ORDER_CANCELLED','LOG_JOB_ORDER_CANCELLED');

	define('LOG_CATEGORY_ARCHIVED','LOG_CATEGORY_ARCHIVED');

	define('LOG_ACCEPT_REFUND','LOG_ACCEPT_REFUND');

	#!-System Log: Log Titles Payload
	$LOG_TITLES = array(
		array('title'=>'System Access Log', 'label'=>LOG_SYSTEM_ENTRY_EXIT, 'tooltip'=>'Contains log for system entry and exit'),
		array('title'=>'Workforce Log', 'label'=>LOG_CATEGORY_WORKFORCE, 'tooltip'=>'Contains log for staff information'),
		array('title'=>'Production Log', 'label'=>LOG_CATEGORY_PRODUCTION, 'tooltip'=>'Contains log for production activities'),
		array('title'=>'Inventory Log', 'label'=>LOG_CATEGORY_INVENTORY, 'tooltip'=>'Contains log for inventory activites'),
		array('title'=>'Job Orders Log', 'label'=>LOG_CATEGORY_JOBS, 'tooltip'=>'Contains log for jobs and related issues'),
		array('title'=>'Refund Log', 'label'=>LOG_ACCEPT_REFUND, 'tooltip'=>'Contains log for refunds'),
		array('title'=>'Archived Log', 'label'=>LOG_CATEGORY_ARCHIVED, 'tooltip'=>'Contains log for deleted staff')
	);

?>
