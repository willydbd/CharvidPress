<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || (!permission_take_job_order() && !permission_view_job_orders() && !permission_accept_refund()) || !isset($_GET['page_view'])) {
		echo "Access denied";
		header("REFRESH:2;URL=$home_url");
		die();
	}
	# !-Redirect unauthorized access

	check_session_token();
	# !-Redirect on session token expiration

	if (isset($_GET['logout'])) {
		logout();
		header("REFRESH:0;URL=$home_url");
		die();
	}
	# !-Logout mechanism

	extract($_GET);
	# !-Setup global values

	if (isset($_POST['add_new_instalment'])) {
		add_new_instalment($_POST);
		js_alert("Job updated successfully", ALERT_TYPE_SUCCESS);
	}
	# !-Handle `add new instalment` form data

	if (isset($_GET['cancel'])) {
		if (permission_cancel_job_orders()) {
			cancel_job_order($_GET['cancel']);
			header("REFRESH:0;URL=$x_link_prefix".$page_view);
			echo "Job order cancelled";
			die();
		}
	}
	# !-Handle `cancel job order` url data

	if (isset($_POST['accept_refund_btn'])) {
		if (permission_accept_refund()) {
			accept_refund($_POST);
			js_alert("Refund accepted successfully", ALERT_TYPE_SUCCESS);
		}
	}
	# !-Handle `accept refund` url data

?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Banjo Mofesola Paul (08065856717) | Planet NEST">
<link rel="icon" href="<?php echo $x_link_prefix ?>images/icon.png" />

	<title>Charvid Digital Press - Front Desk</title>

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo $x_link_prefix ?>css/yeti.bootstrap.3.3.5.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="<?php echo $link_prefix ?>css/style.css" rel="stylesheet">

	<!-- SweetAlert CSS -->
	<link href="<?php echo $link_prefix ?>css/plugins/sweetalert.css" rel="stylesheet">

	<!-- Labelauty CSS -->
	<link href="<?php echo $link_prefix ?>css/plugins/jquery-labelauty.css" rel="stylesheet">

	<!-- Custom Fonts : FontAwesome -->
	<link href="<?php echo $x_link_prefix ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- Animate CSS -->
	<link href="<?php echo $x_link_prefix ?>css/animate.css" rel="stylesheet" type="text/css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>

<div id="watermark"></div>

	<div id="wrapper">

        <nav class="navbar" role="navigation">
        	<div class="container">
                <div class="navbar-header">
                	<img class="img img-responsive" style="height: 150px" src="<?php echo $x_link_prefix ?>images/full_logo.jpg" />
                </div>
                <div class="text-muted" style="padding-top: 3rem">
                    <h3 class="pull-left" style="max-width: 60%"><?php echo $_SESSION['logged']['staff_name'].' ('.$_SESSION['logged']['staff_role'].')' ?></h3>
                    <h5 class="pull-right" style="padding-top: 2rem">
                        <a href="<?php echo $home_url ?>">Dashboard</a>&ensp;|&ensp;
                        <a href="?logout">Logout</a></h5>
                </div>
                <i class="clearfix"></i>
            </div>
        </nav>
	    <!-- /Navigation -->

        <div class="main-container container">

			<div class="well print-nopadding print-noborder" style="background: transparent; border-bottom:none; border-left:none; border-right:none">
            <?php if ($page_view=='new-job') { ?>

                <form action="" method="post" ko="submit: save_order">
                <h4 class=" no-print">Take new job order</h4>
            	<flex>
                	<div class="col-sm-4 well well-sm no-print" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header">Client details</h4>
                        <div class="form-group small">
                        	<label class="control-label">Pick client's details</label>
                            <select class="form-control" ko="options: clients, optionsText: 'name', value: selected_client, optionsCaption: 'Select client details'" readonly>
                            </select>
                        </div>
                        <div class="form-group small">

                            <input type="hidden" name="job_id" value="<? $job_id = new_job_id(); echo $job_id ?>" />

                        	<label class="control-label">Client's name</label>
                            <input type="text" name="client_name" maxlength="80" placeholder="enter client's name" class="form-control" ko="value: client_name, value_update: 'input', enable: !$root.order_submitted()" required />
                        </div>
                        <div class="form-group small">
                        	<label class="control-label">Client's address</label>
                            <input type="text" name="client_address" maxlength="150" placeholder="enter client's address" class="form-control" ko="value: client_add, value_update: 'input', enable: !$root.order_submitted()" />
                        </div>
                        <div class="form-group small">
                        	<label class="control-label">Client's phone number</label>
                            <input type="phone" name="client_phone" placeholder="enter client's phone number" class="form-control" ko="value: client_phone, value_update: 'input', enable: !$root.order_submitted()" />
                        </div>
                        <div class="form-group small">
                        	<label class="control-label">Order description</label>
                            <textarea class="form-control" name="job_description" placeholder="briefly describe this job" maxlength="300" ko="value: order_desc, value_update: 'input', enable: !$root.order_submitted()" required></textarea>
                        </div>
                    </div>
	                <!-- /production-stages-list -->
                    <div class="col-sm-8 well well-sm print-nopadding print-noborder print-fullwidth" style="margin-top: 10px; border-left:none">
                        <h4 class="page-header text-right no-print" style="margin-bottom:0">Order details</h4>

                        <div class="text-right small no-print" style="background:#fff; padding: 1rem 2rem 0">
                        	<a href="#" ko="click: expand_all">Expand all</a>&emsp;
                        	<a href="#" ko="click: collapse_all">Collapse all</a>&emsp;
                        	<a href="#" ko="click: clear_input, visible: !order_submitted()">Clear input</a></div>

                        <div class="print-only">
	                        <img class="img img-responsive print-only pull-left col-xs-2" src="<?php echo $x_link_prefix ?>images/full_logo.jpg" />
                        	<h4 class="text-uppercase"><strong>Charvid Digital Press</strong></h4>
	                        <div class="text-uppercase" style="margin-bottom: 1rem">Invoice</div>
                            <div>
                            	<span style="color: #ccc !important">Order ID: <tt ko="text: job_id"></tt></span>
                                <span style="color: #ccc !important" class="pull-right" ko="text: order_date"></span>
                                <div class="clearfix"></div>
                                <table class="table text-uppercase" style="font-size: medium !important; margin-top: 2rem">
                                	<tr>
                                    	<td class="print-noborder print-no-leftpadding" style="color: #ccc !important; width: 2%; vertical-align: middle">
                                        	Client: &emsp;<span ko="text: client_name()+(client_phone() != ''? ' ('+client_phone()+')':'')"></span></td>
                                    </tr>
                                	<tr>
                                    	<td class="print-noborder print-no-leftpadding" style="color: #ccc !important; width: 2%; vertical-align: middle">
                                        	Address: &emsp;<span ko="text: client_add"></span></td>
                                    </tr>
                                	<tr>
                                    	<td class="print-noborder print-no-leftpadding" style="color: #ccc !important; width: 2%; vertical-align: middle">
                                        	Description: &emsp;<span ko="text: order_desc"></span></td>
                                    </tr>
                                </table>
                            </div>
                            <hr />
                        </div>

                        <div class="print-nopadding print-noborder" style="background: #fff; padding: 1rem 2rem">
                        	<!-- ko foreach: production_stages -->
                            <table class="table table-responsive" style="font-size: 85%; width: 100%" ko="css: total() == '0.00'? 'no-print':''">
                            	<tr>
                                	<th data-toggle="collapse" class="text-uppercase print-noborder btn-primary disabled" ko="attr: { href: '#stage'+id() }" style="color: #fff; cursor: pointer">
                                    	<span ko="text: stage_name"></span>
                                        <span class="pull-right no-print">&#8358;<b ko="text: total"></b></span>
                                    </th>
                                </tr>
                                <tr>
                                	<td style="padding:0" class="collapse in" ko="attr: { id: 'stage'+id() }">
                                    	<table class="table table-bordered table-responsive" style="margin-bottom:0">
                                        	<tr>
                                                <th>Production Process</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                            <!-- ko foreach: processes -->
                                            <tr ko="css: price() == '0.00'? 'no-print':''">
                                                <td ko="text: process_name" style="vertical-align:middle"></td>
                                                <td style="max-width: 100px">
                                                    <div class="form-group small" style="margin-bottom:0">
                                                        <div class="input-group small no-print">
                                                            <input class="form-control input-sm text-right" type="number" min="0" ko="value: qty, value_update: 'input', enable: !$root.order_submitted()" />
                                                            <small class="input-group-addon" style="font-size: 140%" ko="text: process_unit"></small>
                                                        </div>
                                                    </div>
                                                        <span class="print-only" ko="text: typeof(qty()) != typeof(undefined)? qty()+' '+process_unit():''"></span>
                                                </td>
                                                <td class="text-right" style="vertical-align:middle">&#8358;<b ko="text: price"></b></td>
                                            </tr>
                                            <!-- /ko -->
                                            <tr>
                                                <th colspan="3">
                                                	Total<span class="pull-right">&#8358;<b ko="text: total"></b></span></th>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- /ko -->
                            <div class="col-sm-5 btn-group btn-group-sm btn-group-vertical text-uppercase no-print" style="padding-left:0">
                            	<input type="submit" class="btn btn-primary text-uppercase" value="Submit Order" name="save_order_btn" ko="css: order_submitted() || processing()? 'disabled':''" />
                            	<a class="btn btn-primary" ko="click: raise_invoice, css: order_submitted()? '':'disabled'">Raise Invoice</a>
                            	<a class="btn btn-primary" ko="click: page_reload">Reset Order Page</a>
                            </div>
                            <div class="col-sm-5 col-sm-offset-2 print-fullwidth print-nopadding" style="padding-right:0">
                                <table class="table table-bordered table-responsive btn-primary disabled" style="color: #fff; margin:0">
                                   <tr class="no-print">
                                        <td colspan="2">
                                            <div class="form-group small">
                                                <label class="control-label small">Deposit:</label>
                                                <input name="reimbursement" placeholder type="number" class="form-control input-sm" ko="value: deposit, value_update: 'input', enable: !$root.order_submitted()" min="0" />
                                            </div>
                                            <div class="form-group small">
                                                <label class="control-label small">Discount:</label>
                                                <input name="discount" placeholder type="number" class="form-control input-sm" ko="value: discount, value_update: 'input', enable: !$root.order_submitted()" min="0" />
                                            </div>
                                            <div class="form-group small">
                                                <label class="control-label small">Handling Charges:</label>
                                                <input name="handling_charges" placeholder type="number" class="form-control input-sm" ko="value: handling_charges, value_update: 'input', enable: !$root.order_submitted()" min="0" />
                                            </div>
                                            <div class="form-group small">
                                                <label class="control-label small">Service Charges:</label>
                                                <input name="profit" placeholder type="number" class="form-control input-sm" ko="value: profit, value_update: 'input', enable: !$root.order_submitted()" min="0" />
                                            </div>
                                            <div class="form-group small">
                                                <label class="control-label small">Sales Commission:</label>
                                                <input name="sales_comm" placeholder type="number" class="form-control input-sm" ko="value: sales_comm, value_update: 'input', enable: !$root.order_submitted()" min="0" />
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="print-only print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Deposit</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(deposit())"></b></span></th>
                                    </tr>
                                    <tr class="print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Lump Sum</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: lump_sum"></b></span></th>
                                    </tr>
                                    <tr class="print-only print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Handling Charges</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: receipt_handling"></b></span></th>
                                    </tr>
                                    <tr class="print-only print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Service Charges</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(profit())"></b></span></th>
                                    </tr>
                                    <tr class="print-only print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Discount</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(discount())"></b></span></th>
                                    </tr>
                                    <tr class="print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">VAT</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: vat"></b></span></th>
                                    </tr>
                                    <tr class="print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Final Cost</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: final_cost"></b></span></th>
                                    </tr>
                                    <tr class="print-noborder">
                                        <th class="col-sm-6 text-right"><span class="text-uppercase">Balance</span></th>
                                        <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: balance"></b></span></th>
                                    </tr>
                                </table>
                                <small class="print-only">Printed on <span ko="text: todays_date"></span></small>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
	                <!-- /production-stages-details -->
                </flex>
                </form>

            <?php } elseif ($page_view=='pending-jobs' || $page_view=='completed-jobs') { ?>

                <flex>
                    <div class="col-sm-3 well well-lg no-print" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header" style="letter-spacing:-1px"><?php echo $page_view=='pending-jobs'? 'Pending Jobs':'Completed Jobs' ?></h4>
                        <h4 ko="visible: jobs().length == 0" class="text-muted text-center small">
                        	<?php echo $page_view=='pending-jobs'? 'No pending jobs':'No completed jobs' ?>
                        </h4>
                        <!-- ko if: jobs().length > 0 -->
                        <?php if ($page_view=='completed-jobs' && permission_view_revenue_overview()) {  ?><a class="no-decor small text-uppercase btn-block" ko="click: toggle_revenue_overview" style="cursor:pointer; padding: .5rem 0; font-weight: bold">
                        	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: viewing_revenue_overview"></span>
                        	View revenue overview
                        </a><?php } ?>
                        <div ko="foreach: jobs" class="small text-uppercase" style="font-weight: bold">
                        	<a class="no-decor btn-block" ko="click: $root.job_selected, attr: { title: client_name+' ('+job_description+')' }" style="cursor:pointer; padding: .5rem 0">
                            	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: $root.is_selected( job_id )"></span>
                                <span ko="text: job_id"></span>
                            </a>
                        </div>
                        <!-- /ko -->
                    </div>
	                <!-- /jobs-list -->
                    <div class="col-sm-9 well well-sm print-noborder print-fullwidth" style="margin-top: 10px; border-left:none">
                    	<div class="not-receipt" ko="if: !viewing_revenue_overview()">
						<h4 ko="visible: !selected()" class="text-muted text-center small">
                        	Click on a job from the list on the left to view details
                        </h4>
                        <div ko="visible: selected, with: selected" class="print-nopadding print-noborder print-fullwidth" style="background: #fff; padding: 1rem 2rem">
                        	<div>
	                        	<img class="img img-responsive print-only pull-left col-xs-2" src="<?php echo $x_link_prefix ?>images/full_logo.jpg" />
                                <h4 class="print-only text-uppercase"><strong>Charvid Digital Press</strong></h4>
                                <div class="print-only text-uppercase" style="margin-bottom: 1rem">Invoice</div>
                                <div>
                                    <span style="color: #777 !important">Order ID: <tt ko="text: job_id"></tt></span>
                                    <span style="color: #777 !important" class="pull-right" ko="text: order_date"></span>

                                    <div class="clearfix"></div>
                                    <table class="table text-uppercase" style="font-size: medium !important; margin-top: 2rem">
                                        <tr>
                                            <td class="no-print" style="color: #777 !important; width: 2%; vertical-align: middle">
                                                Staff-in-charge: &emsp;<span ko="text: staff_in_charge"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="print-noborder print-no-leftpadding" style="color: #777 !important; width: 2%; vertical-align: middle">
                                                Client: &emsp;<span ko="text: client_name+(client_phone != ''? ' ('+client_phone+')':'')"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="print-noborder print-no-leftpadding" style="color: #777 !important; width: 2%; vertical-align: middle">
                                                Address: &emsp;<span ko="text: client_address"></span></td>
                                        </tr>
                                        <tr>
                                            <td class="print-noborder print-no-leftpadding" style="color: #777 !important; width: 2%; vertical-align: middle">
                                                Description: &emsp;<span ko="text: job_description"></span></td>
                                        </tr>
                                    </table>
                                </div>
                                <hr />
                                <div ko="if: order">
                                	<!-- ko foreach: order -->
                                    <table class="table table-responsive" style="font-size: 85%; width: 100%">
                                        <tr>
                                            <th class="text-uppercase print-noborder btn-primary disabled" style=" color: #fff">
                                                <span ko="text: stage_name"></span>
                                                <span class="pull-right no-print">&#8358;<b ko="text: total"></b></span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td style="padding:0">
                                                <table class="table table-bordered table-responsive" style="margin-bottom:0">
                                                    <tr>
                                                        <th>Production Process</th>
                                                        <th>Quantity</th>
                                                        <th>Price</th>
                                                    </tr>
                                                    <!-- ko foreach: processes -->
                                                    <tr>
                                                        <td ko="text: process_name" style="vertical-align:middle"></td>
                                                        <td style="max-width: 100px">
                                                            <span ko="text: qty+' '+process_unit"></span>
                                                        </td>
                                                        <td class="text-right" style="vertical-align:middle">&#8358;<b ko="text: price"></b></td>
                                                    </tr>
                                                    <!-- /ko -->
                                                    <tr>
                                                        <th colspan="3">
                                                            Total<span class="pull-right">&#8358;<b ko="text: total"></b></span></th>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- /ko -->
                                    <div class="col-sm-5" style="padding: 0">
                                    <?php if ($page_view=='pending-jobs' && permission_update_pending_jobs()) { ?>
                                    	<div class="btn-primary no-print disabled" style="padding: 1px 1rem">
                                            <div class="small" style="margin: 1rem 0">Update payment details for this job</div>
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <input type="hidden" name="job_id" ko="value: job_id" />
                                                    <input type="hidden" name="job_desc" ko="value: job_description" />
                                                    <input class="form-control" name="new_instalment" type="number" min="1" placeholder="new instalment" required />
                                                    <input class="form-control btn btn-default" type="submit" name="add_new_instalment" value="Update" />
                                                </div>
                                            </form>
                                        </div>
                                    <?php } ?>
	                                    <a class="btn btn-info active btn-block text-uppercase no-print" ko="click: $root.raise_invoice">Raise Invoice</a>
	                                    <a class="btn btn-primary btn-block text-uppercase no-print" style="margin:0" ko="click: $root.print_receipt">Print Receipt</a>
	                                    <a class="btn btn-primary active btn-block text-uppercase no-print" style="margin:0" ko="click: $root.pos_receipt">POS Receipt</a>
                                    <?php if (permission_cancel_job_orders()) { ?>
                                    	<a ko="click: $root.cancel_job_order" class="btn btn-block text-uppercase no-print" style="margin:0; background: gray; color: #fff">Cancel Order</a>
                                    <?php } ?>
                                    </div>
                                    <div class="col-sm-5 col-sm-offset-2 print-fullwidth print-nopadding" style="padding-right:0">
                                        <table class="table table-bordered table-responsive btn-primary disabled" style="color: #fff; margin:0">
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Deposit</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(reimbursement)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Lump Sum</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(job_estimate)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Handling Charges</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(parseFloat(handling_charges) + parseFloat(sales_comm))"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Service Charges</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(profit)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Discount</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(discount)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">VAT</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(vat)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Final Cost</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;<b ko="text: addCommas(final_cost)"></b></span></th>
                                            </tr>
                                            <tr class="print-noborder">
                                                <th class="col-sm-6 text-right"><span class="text-uppercase">Balance</span></th>
                                                <th class="col-sm-6"><span class="text-uppercase">&#8358;
                                                <b ko="text: addCommas(balance)"></b></span></th>
                                            </tr>
                                        </table>
                                        <?php if ($page_view=='completed-jobs') { ?>
                                        	<small>Paid in full on <span ko="text: completion_date"></span></small>
										<?php } else { ?>
                                        	<small class="print-only">Printed on <span ko="text: $root.todays_date"></span></small>
										<?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- /content apart from receipt -->

                        <?php if ($page_view=='completed-jobs' && permission_view_revenue_overview()) {  ?>
                        <div class="no-print" ko="visible: viewing_revenue_overview" style="background: #fff; padding: 1rem 2rem">
                        	<table class="table table-bordered table-responsive" style="margin-bottom:0">
                            	<tr>
                                	<th class="btn-primary disabled">Month</th>
                                    <th class="btn-primary disabled">Revenue (&#8358;)</th>
                                    <th class="btn-primary disabled">VAT (&#8358;)</th>
                                    <th class="btn-primary disabled">Refund (&#8358;)</th>
                                </tr>
                                <?php
                                	$rev = get_revenue_overview();
									foreach ($rev as $m=>$r) { extract($r); ?>
                                <tr>
                                	<td><?php echo $m ?></td>
                                	<td><?php echo number_format($revenue, 2) ?></td>
                                	<td><?php echo number_format($vat, 2) ?></td>
                                	<td><?php echo number_format($refund, 2) ?></td>
                                </tr>
															<?php } ?>
                            </table>
                        </div>
											<?php } ?>
                        <!-- /revenue overview -->

                        <!-- RECEIPT -->
                        <?php include('lib/include-pending-receipt.php'); ?>
                        <!-- /RECEIPT -->

                    </div>
                </flex>

            <?php } elseif ($page_view=='accept-refund') { ?>
            	<form action="" method="post" class="text-muted" ko="submit: accept_refund_form_submit">
                    <div class="col-sm-4 col-sm-offset-4 well well-lg" style="margin-top: 10px">
                        <h4 class="page-header">Accept refund</h4>
                        <div class="form-group">
                            <label class="control-label">Staff <em>(who is refunding)</em></label>
                            <!--<select name="staff" class="form-control" ko="value: staff" required>
                            	<option value="" style="display: none">Select staff</option>
                                <?php
                                $staff = get_all_staff(false);
								foreach ($staff as $s) { ?>
                                <option value="<?php echo $s['staff_name'] ?>"><?php echo $s['staff_name'].' ('.$s['staff_role'].')' ?></option>
															<?php } ?>
                            </select>-->
                            <input type="text" name="staff" class="form-control" ko="value: staff" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Purpose <em>(refund for what)</em></label>
                            <textarea name="purpose" placeholder="enter refund purpose" ko="value: purpose" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Amount</label>
                            <input type="number" name="amount" placeholder="enter the amount" min="1" ko="value: amount" class="form-control" required />
                        </div>
                        <div class="form-group">
	                    	<input type="submit" name="accept_refund_btn" value="Accept" class="btn-block btn btn-primary" />
                        </div>
                    </div>
                </form>
            <?php } ?>
			</div>
	        <!-- /content -->

            <footer class="text-center col-sm-12 not-receipt">
            	<p>
                	<strong class="text-muted">Charvid Digital Press, <?php echo date('Y'); ?></strong><br />
                    <small style="color:blue">Powered by TCS</small>
                </p>
            </footer>
	        <!-- /.footer -->

        </div>
        <!-- /.container -->

    </div>
    <!-- /#wrapper -->


    <!-- jQuery -->
    <script src="<?php echo $x_link_prefix ?>js/jquery.1.11.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $x_link_prefix ?>js/bootstrap.3.3.5.min.js"></script>

    <!-- SweetAlert JavaScript -->
    <script src="<?php echo $x_link_prefix ?>js/plugins/sweetalert.min.js"></script>

    <!-- Labelauty JavaScript -->
    <script src="<?php echo $x_link_prefix ?>js/plugins/jquery-labelauty.js"></script>

	<!-- Knockout -->
	<script src="<?php echo $x_link_prefix ?>js/ko.min.js"></script>
	<script src="<?php echo $x_link_prefix ?>js/knockout.mapping.js"></script>

	<!-- NumberToEnglish - StackOverflow -->
	<script src="<?php echo $x_link_prefix ?>js/plugins/numbertowords.js"></script>

    <!-- Custom JavaScript -->
    <script>var where = '<?php echo $page_view ?>'</script>
    <script src="<?php echo $link_prefix ?>js/script.js"></script>
    <script>Me.url_prefix = '<?php echo $link_prefix ?>';</script>

</body>

</html>
