<?php

	/*
	 * Checks whether current staff is able to manage other staff
	 * Return type: boolean
	 */
	function permission_manage_staff() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['manage_staff'];
	}

	/*
	 * Checks whether current staff is able to take new job orders
	 * Return type: boolean
	 */
	function permission_take_job_order() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['take_job_order'];
	}

	/*
	 * Checks whether current staff is able to manage production
	 * Return type: boolean
	 */
	function permission_manage_production() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['manage_production'];
	}

	/*
	 * Checks whether current staff is able to manage inventory
	 * Return type: boolean
	 */
	function permission_manage_inventory() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['manage_inventory'];
	}

	/*
	 * Checks whether current staff is able to get notifications on inventory
	 * Return type: boolean
	 */
	function permission_inventory_notification() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['inventory_notification'];
	}

	/*
	 * Checks whether current staff is able to get inventory overview
	 * Return type: boolean
	 */
	function permission_inventory_overview() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['inventory_overview'];
	}

	/*
	 * Checks whether current staff is able to view job orders
	 * Return type: boolean
	 */
	function permission_view_job_orders() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['view_job_orders'];
	}

	/*
	 * Checks whether current staff is able to view update pending job orders
	 * Return type: boolean
	 */
	function permission_update_pending_jobs() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['update_pending_jobs'];
	}

	/*
	 * Checks whether current staff is able to view system log
	 * Return type: boolean
	 */
	function permission_view_system_log() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['view_system_log'];
	}

	/*
	 * Checks whether current staff is able to view revenue overview
	 * Return type: boolean
	 */
	function permission_view_revenue_overview() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['view_revenue_overview'];
	}

	/*
	 * Checks whether current staff is able cancel job orders
	 * Return type: boolean
	 */
	function permission_cancel_job_orders() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['cancel_job_orders'];
	}

	/*
	 * Checks whether current staff is able accept refund
	 * Return type: boolean
	 */
	function permission_accept_refund() {
		return isset($_SESSION['logged']['roles']) && $_SESSION['logged']['roles']['accept_refund'];
	}

?>
