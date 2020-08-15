<?php

	include('lib/db.php');
	# !-Link up to db

	if (isset($_GET['logout'])) {
		logout();
		header("REFRESH:0;URL=$home_url");
		die();
	}
	# !-Logout mechanism

?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Williams Aregbesola (08066619744) | TCS  | Banjo Mofesola Paul (08065856717) | Planet NEST">
<link rel="icon" href="<?php echo $x_link_prefix ?>images/icon.png" />

	<title>Charvid Digital Press</title>

	<!-- Bootstrap Core CSS -->
	<link href="<?php echo $x_link_prefix ?>css/yeti.bootstrap.3.3.5.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="<?php echo $link_prefix ?>css/style.css" rel="stylesheet">

	<!-- SweetAlert CSS -->
	<link href="<?php echo $link_prefix ?>css/plugins/sweetalert.css" rel="stylesheet">

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

<div id="watermark" class="no-fade"></div>

<?php if (!$logged) { ?>
	<div id="wrapper" style="height: 100%">

        <!-- Navigation -->
        <nav class="navbar" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href=""></a>
            </div>
        </nav>

		<div class="container ab-fullscreen">

        	<div class="col-sm-4 col-sm-offset-4 well well-lg" style="margin-top: 10px">
            	<h4 class="page-header">Authorization required</h4>
                <form class="text-muted" id="login-form">
                	<div class="form-group">
                    	<label class="control-label" for="#login_username">Please provide your login username</label>
                        <input type="text" id="login_username" ko="value: username, value_update: 'input', textInput: username" placeholder="enter login username" class="form-control" required />
                    </div>
                	<div class="form-group">
                    	<label class="control-label" for="#login_pswd">Enter your password</label>
                        <div class="input-group">
	                    	<input type="password" id="login_pswd" ko="value: pswd, value_update: 'input', textInput: pswd" placeholder="enter password" class="form-control" required />
                            <a class="btn btn-primary input-group-addon pswd-reveal-btn"><span class="fa fa-eye"></span></a>
						</div>
                    </div>
                   	<span class="alrt"></span>
                	<div class="form-group text-center">
                    	<a class="btn btn-default" ko="click: login_clicked">
                        	<i class="fa fa-fw fa-spin fa-spinner no-display" ko="visible: processing"></i>
                            <span ko="text: processing()? 'Verifying...':'Enter'"></span>
                        </a>
                    </div>
                </form>
            </div>
            <!-- /login-pane -->

            <footer class="text-center col-sm-12 colored">
            	<p>
                	<strong class="text-muted">Charvid Digital Press, <? echo date('Y'); ?></strong><br />
                    <small style="color:blue">Powered by TCS</small>
                </p>
            </footer>
	        <!-- /.footer -->

        </div>
        <!-- /.container -->

    </div>
    <!-- /#wrapper -->

<?php } ?>
<!-- /#Login -->


<?php if ($logged) { check_session_token(); ?>
	<div id="wrapper">

        <nav class="navbar" role="navigation">
        	<div class="container">
                <div class="navbar-header">
                	<img class="img img-responsive" style="height: 150px" src="<?php echo $x_link_prefix ?>images/full_logo.jpg" />
                </div>
                <div class="text-muted" style="padding-top: 3rem">
                    <h3 class="pull-left" style="max-width: 80%"><?php echo $_SESSION['logged']['staff_name'].' ('.$_SESSION['logged']['staff_role'].')' ?></h3>
                    <h5 class="pull-right" style="padding-top: 2rem"><a href="?logout">Logout</a></h5>
                </div>
                <i class="clearfix"></i>
            </div>
        </nav>
	    <!-- /Navigation -->

        <div class="container">

			<div class="well well-sm" style="background: transparent; border-bottom:none; border-left:none; border-right:none">
			<?php if (permission_inventory_notification()) {
				$out_of_stock = count(get_out_of_stock_items());
				$at_thresh = count(get_threshold_items());
			?>
            	<?php if ($out_of_stock != 0) { ?>
                <div class="alert alert-danger alert-dismissible">
                	<button data-dismiss="alert" class="btn close" aria-hidden="true">&times;</button>
                	<h4><a class="alert-link" href="out-of-stock"><? echo $out_of_stock.(($out_of_stock == 1)? ' item</a> is':' items</a> are') ?></a> out of stock!</h4>
                </div>
							<?php } ?>

            	<?php if ($at_thresh != 0) { ?>
                <div class="alert alert-warning alert-dismissible">
                	<button data-dismiss="alert" class="btn close" aria-hidden="true">&times;</button>
                	<h4>Supply is running low for <a class="alert-link" href="almost-finished"><? echo $at_thresh.(($at_thresh == 1)? ' item':' items') ?></a> in the inventory!</h4>
                </div>
							<?php } ?>

            <?php } ?>

			<?php if (permission_manage_staff()) { ?>
               	<a class="dashboard-menu-item" href="view-all-staff">
                	<h5 class="text-center"><b>View Staff list</b></h5>
                    <p>
                        Select this option to see all registered members of staff on the system
                    </p>
                </a>
               	<a class="dashboard-menu-item" href="add-staff">
                	<h5 class="text-center"><b>Add New Staff</b></h5>
                    <p>
                        Select this option to add a new member of staff to the system and assign roles
                    </p>
                </a>
                <a class="dashboard-menu-item" href="edit-staff-info">
                    <h5 class="text-center"><b>Edit Staff Info</b></h5>
                    <p>
                        Change information and roles of existing member of staff
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_manage_production()) { ?>
            	<a class="dashboard-menu-item" href="add-production-stage">
                	<h5 class="text-center"><b>Add Production Stage</b></h5>
                    <p>
                        Select this option to add production stages <em>(e.g. Pre-press, Press, etc.)</em>
                    </p>
                </a>
               	<a class="dashboard-menu-item" href="manage-production-stages">
                	<h5 class="text-center"><b>Manage Production Stages</b></h5>
                    <p>
                        Add or remove production processes <em>(e.g. type-setting, designing, etc.)</em> in production stages, delete production stages
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_manage_inventory()) { ?>
            	<a class="dashboard-menu-item" href="add-inventory-item">
                	<h5 class="text-center"><b>Add items to inventory</b></h5>
                    <p>
                        Select this option to add items to the inventory, grouping them by categories
                    </p>
                </a>
            	<a class="dashboard-menu-item" href="manage-inventory">
                	<h5 class="text-center"><b>Manage Inventory</b></h5>
                    <p>
                        Manage inventory items, update information, remove items.
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_inventory_overview()) { ?>
            	<a class="dashboard-menu-item" href="see-inventory-overview">
                	<h5 class="text-center"><b>View Inventory Overview</b></h5>
                    <p>
                        Select this option to see an overview of the inventory, showing the worth per category and total stock worth
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_accept_refund()) { ?>
            	<a class="dashboard-menu-item" href="accept-refund">
                	<h5 class="text-center"><b>Accept Refund</b></h5>
                    <p>
                        Receive refund from staff
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_take_job_order()) { ?>
            	<a class="dashboard-menu-item" href="new-job">
                	<h5 class="text-center"><b>New Job Order</b></h5>
                    <p>
                        Take a new job order, process job and generate receipt
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_view_job_orders()) { ?>
            	<a class="dashboard-menu-item" href="pending-jobs">
                	<h5 class="text-center"><b>Pending Jobs</b></h5>
                    <p>
                        View and update payment details for all jobs that have not been paid in full
                    </p>
                </a>
            	<a class="dashboard-menu-item" href="completed-jobs">
                	<h5 class="text-center"><b>Completed Jobs</b></h5>
                    <p>
                        View and review all jobs that have been paid in full
                    </p>
                </a>
            <?php } ?>
            <?php if (permission_view_system_log()) { ?>
            	<a class="dashboard-menu-item" href="system-log">
                	<h5 class="text-center"><b>System Log</b></h5>
                    <p>
                        Monitor system-wide activities from the detailed System Log
                    </p>
                </a>
            <?php } ?>
            </div>
            <div class="clearfix"></div>

	        <!-- /content -->

            <footer class="text-center col-sm-12 colored">
            	<p>
                	<strong class="text-muted">Charvid Digital Press, <? echo date('Y'); ?></strong><br />
                    <small style="color:blue">Powered by TCS</small>
                </p>
            </footer>
	        <!-- /.footer -->

        </div>
        <!-- /.container -->

    </div>
    <!-- /#wrapper -->

<?php } ?>
<!-- /#Dashboard -->

<!-- jQuery -->
    <script src="<?php echo $x_link_prefix ?>js/jquery.1.11.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $x_link_prefix ?>js/bootstrap.3.3.5.min.js"></script>

    <!-- SweetAlert JavaScript -->
    <script src="<?php echo $x_link_prefix ?>js/plugins/sweetalert.min.js"></script>

    <!-- jQuery Validate -->
    <script src="<?php echo $x_link_prefix ?>js/plugins/jquery.validate.min.js"></script>

	<!-- Knockout -->
	<script src="<?php echo $x_link_prefix ?>js/ko.min.js"></script>
	<script src="<?php echo $x_link_prefix ?>js/knockout.mapping.js"></script>

    <!-- Custom JavaScript -->
    <script>var where = '<?php echo (!$logged)? 'login':'dashboard' ?>'</script>
    <script src="<?php echo $link_prefix ?>js/script.js"></script>
    <script>Me.url_prefix = '<?php echo $link_prefix ?>';</script>
</body>

</html>
