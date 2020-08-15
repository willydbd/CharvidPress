<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_manage_staff() || !isset($_GET['page_view'])) {
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

	if ($page_view == 'delete-staff') {
		delete_staff($who);
		header("REFRESH:0;URL=".$home_url."edit-staff-info");
		die();
	}
	# !-Handle 'delete staff' data

	if (isset($_POST['add_staff_btn'])) {
		if (staff_exists($_POST['staff_id'])) {
			js_alert("Username is unavailable", ALERT_TYPE_FAILED);
		} else {
			add_staff($_POST);
			js_alert("Staff added successfully", ALERT_TYPE_SUCCESS);
		}
	}
	# !-Handle `new staff` form data

	if (isset($_POST['update_staff_btn'])) {
		add_staff($_POST);
		js_alert("Details updated successfully", ALERT_TYPE_SUCCESS);
	}
	# !-Handle `update staff info` form data

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<link rel="icon" href="<?php echo $x_link_prefix ?>images/icon.png" />

    <title>Charvid Digital Press - Managing Staff</title>

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

        <div class="container">

			<div class="well" style="background: transparent; border-bottom:none; border-left:none; border-right:none">
			<?php if ($page_view=='add-staff') { ?>

                <form action="" method="post" class="text-muted" id="add-staff-form" ko="submit: add_staff_form_submit">
                	<flex>
                    <div class="col-sm-4 col-sm-offset-2 well well-lg" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header">Add new staff member</h4>
                        <div class="form-group">
                            <label class="control-label" for="#staff_name">Staff name</label>
                            <input type="text" name="staff_name" placeholder="enter staff name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="#staff_role">Staff job role <em>e.g. Storekeeper</em></label>
                            <input type="text" name="staff_role" placeholder="enter staff job role" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="#staff_id">Staff username &mdash; for login</label>
                            <input type="text" name="staff_id" placeholder="enter staff login username" class="form-control" maxlength="10" required />
                        </div>
                        <!--<div class="form-group">
                            <label class="control-label">Set staff password &mdash; click button to change</label>
                            <div class="input-group">
                                <input name="staff_pswd" type="text" ko="value: staff_pswd" class="form-control" required readonly />
                            	<a class="btn btn-primary input-group-addon" ko="click: change_pswd"><span class="fa fa-refresh"></span></a>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="control-label">Set staff password</label>
                            <input name="staff_pswd" type="text" ko="value: staff_pswd" class="form-control" placeholder="enter staff password" required />
                        </div>
                    </div>
                    <!-- /staff-detail-pane -->
                    <div class="col-sm-4 well well-lg" style="margin-top: 10px; border-left:none">
                        <h4 class="page-header">
                            Set permissions
                            <span class="small"><small>Permissions determine what actions a staff can perform on the system</small></span></h4>
                        <input type="checkbox" data-labelauty="Take Job Orders" name="perms[take_job_order]" />
                        <input type="checkbox" data-labelauty="View Job Orders" name="perms[view_job_orders]" />
                        <input type="checkbox" data-labelauty="Accept Refund" name="perms[accept_refund]" />
                        <input type="checkbox" data-labelauty="Update Pending Jobs" name="perms[update_pending_jobs]" />
                        <input type="checkbox" data-labelauty="Cancel Job Orders" name="perms[cancel_job_orders]" />
                        <input type="checkbox" data-labelauty="Oversee Production" name="perms[manage_production]" />
                        <input type="checkbox" data-labelauty="Manage Staff" name="perms[manage_staff]" />
                        <input type="checkbox" data-labelauty="Manage Inventory" name="perms[manage_inventory]" />
                        <input type="checkbox" data-labelauty="Get Inventory Notifications" name="perms[inventory_notification]" />
                        <input type="checkbox" data-labelauty="Access Inventory Overview" name="perms[inventory_overview]" />
                        <input type="checkbox" data-labelauty="View Revenue Overview" name="perms[view_revenue_overview]" />
                        <input type="checkbox" data-labelauty="View System Log" name="perms[view_system_log]" />
                    </div>
                    </flex>
                    <!-- /staff-permissions-pane -->
                    <p class="text-center col-sm-8 col-sm-offset-2">
                    	<input type="submit" name="add_staff_btn" value="Add Staff" class="btn-block btn btn-primary" />
                    </p>
                </form>

            <?php } elseif ($page_view=='view-all-staff') { $data = get_all_staff(); $i = 1; ?>

                <h4>Staff List</h4>
                <table class="table table-responsive table-striped">
                	<tr>
                    	<th style="border-top:none">S/N</th>
                    	<th style="border-top:none">Name</th>
                    	<th style="border-top:none">Role</th>
                    	<th style="border-top:none">Reg date</th>
                    	<th style="border-top:none">Permissions</th>
                    </tr>
                    <?php foreach($data as $d) { extract($d) ?>
                    <tr>
                    	<td><?php echo $i ?>.</td>
                    	<td><?php echo $staff_name ?></td>
                    	<td><?php echo $staff_role ?></td>
                    	<td><?php echo date('F dS Y (H:i)', strtotime($add_date)) ?></td>
                    	<td><?php echo implode('<br />', $roles) ?></td>
                    </tr>
                    <?php $i++; } ?>
                </table>
                <!-- /staff-list-table -->

            <?php } elseif ($page_view=='edit-staff-info') { ?>

                <h4>Edit Staff Info</h4>
                <flex>
                    <div class="col-sm-3 well well-lg" style="margin-top: 10px; border-right:none">
                    	<h4 class="page-header">All Staff</h4>
                        <div ko="foreach: staff_list" class="small text-uppercase" style="font-weight: bold">
                        	<a class="no-decor btn-block" ko="click: $root.staff_selected" style="cursor:pointer; padding: .5rem 0">
                            	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: $root.is_selected( staff_id )"></span>
                                <span ko="text: staff_name"></span>
                            </a>
                        </div>
                    </div>
                    <!-- /staff-list-pane -->
                    <div class="col-sm-9 well well-sm" style="margin-top: 10px; border-left:none">
						<h4 ko="visible: !selected()" class="text-muted text-center small">
                        	Click on a staff from the list on the left to edit their details
                        </h4>
                    	<form action="" method="post" style="display:none; background: #fff; padding: 1rem 2rem" ko="visible: selected, with: selected, submit: update_staff_form_submit">

                            <div class="text-right small no-print" style="background:#fff; padding: 1rem 2rem 0">
                                <a ko="attr: { href: 'delete-staff?who='+staff_id }, click: $root.confirm_deletion">Delete staff</a></div>

                            <div class="form-group">
                                <label class="control-label">Staff name</label>
                                <input type="text" name="staff_name" placeholder="enter staff name" class="form-control" ko="value: staff_name, value_update: 'input'" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Staff job role <em>e.g. Storekeeper</em></label>
                                <input type="text" name="staff_role" placeholder="enter staff job role" class="form-control" ko="value: staff_role, value_update: 'input'" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Staff username &mdash; for login</label>
                                <input type="text" name="staff_id" placeholder="enter staff login username" class="form-control" maxlength="10" ko="value: staff_id" readonly required />
                            </div>
                            <!--<div class="form-group">
                                <label class="control-label">Set staff password &mdash; click button to change</label>
                                <div class="input-group">
                                    <input name="staff_pswd" type="text" ko="value: staff_pswd" class="form-control" required readonly />
                                    <a class="btn btn-primary input-group-addon" ko="click: $root.change_pswd"><span class="fa fa-refresh"></span></a>
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="control-label">Staff password</label>
                                <div class="input-group">
                                    <input type="password" id="staff_pswd" ko="value: staff_pswd, value_update: 'input', textInput: staff_pswd" placeholder="enter staff password" class="form-control" required />
                                    <a class="btn btn-primary input-group-addon pswd-reveal-btn"><span class="fa fa-eye"></span></a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Permissions</label>
                                <div>
                                    <input type="checkbox" perm="Take_job_orders" data-labelauty="Take Job Orders" name="perms[take_job_order]" />
                                    <input type="checkbox" perm="View_job_orders" data-labelauty="View Job Orders" name="perms[view_job_orders]" />
                                    <input type="checkbox" perm="Accept_refund" data-labelauty="Accept Refund" name="perms[accept_refund]" />
                                    <input type="checkbox" perm="Update_pending_jobs" data-labelauty="Update Pending Jobs" name="perms[update_pending_jobs]" />
                                    <input type="checkbox" perm="Cancel_job_orders" data-labelauty="Cancel Job Orders" name="perms[cancel_job_orders]" />
                                    <input type="checkbox" perm="Oversee_production" data-labelauty="Oversee Production" name="perms[manage_production]" />
                                    <input type="checkbox" perm="Manage_staff" data-labelauty="Manage Staff" name="perms[manage_staff]" />
                                    <input type="checkbox" perm="Manage_inventory" data-labelauty="Manage Inventory" name="perms[manage_inventory]" />
			                        <input type="checkbox" perm="Get_inventory_notifications" data-labelauty="Get Inventory Notifications" name="perms[inventory_notification]" />
			                        <input type="checkbox" perm="Access_inventory_overview" data-labelauty="Access Inventory Overview" name="perms[inventory_overview]" />
			                        <input type="checkbox" perm="View_revenue_overview" data-labelauty="View Revenue Overview" name="perms[view_revenue_overview]" />
			                        <input type="checkbox" perm="View_system_log" data-labelauty="View System Log" name="perms[view_system_log]" />
                                </div>
                            </div>
                            <div class="form-group text-center">
                            	<input type="submit" value="Update" name="update_staff_btn" class="btn btn-default" />
                            </div>
                        </form>
                    </div>
                    <!-- /staff-details-pane -->
                </flex>

            <?php } ?>
            </div>
	        <!-- /content -->

            <footer class="text-center col-sm-12">
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

    <!-- Custom JavaScript -->
    <script>var where = '<?php echo $page_view ?>'</script>
    <script src="<?php echo $link_prefix ?>js/script.js"></script>
    <script>Me.url_prefix = '<?php echo $link_prefix ?>';</script>

</body>

</html>
