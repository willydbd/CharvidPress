<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_manage_production() || !isset($_GET['page_view'])) {
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

	if (isset($_POST['add_pdtn_stage_btn'])) {
		if (production_stage_exists($_POST['stage_name'])) {
			js_alert("Production stage exists already", ALERT_TYPE_FAILED);
		} else {
			add_production_stage($_POST);
			js_alert("Production stage added successfully", ALERT_TYPE_SUCCESS);
		}
	}
	# !-Handle `new production stage` form data

	if (isset($_POST['update_pdtn_stage_btn'])) {
		update_production_stage($_POST);
		js_alert("Production stage updated successfully", ALERT_TYPE_SUCCESS);
	}
	# !-Handle `update production stage` form data

	if (isset($_GET['del-stage'])) {
		remove_production_stage($_GET['del-stage']);
		header("REFRESH:0;URL=manage-production-stages");
		die();
	}
	# !-Handle `remove production stage` form data

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

    <title>Charvid Digital Press - Managing Production</title>

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
            <?php if ($page_view=='add-production-stage') { ?>

                <form action="" method="post" class="text-muted">
                    <div class="col-sm-4 col-sm-offset-4 well well-lg" style="margin-top: 10px">
                        <h4 class="page-header">Add new production stage</h4>
                        <div class="form-group">
                            <label class="control-label" for="#staff_name">Production stage <em>(e.g Pre-press)</em></label>
                            <input type="text" name="stage_name" placeholder="enter stage name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="#staff_name">Rank <em>(order or occurrence)</em></label>
                            <input type="number" name="stage_rank" placeholder="enter stage rank" min="1" class="form-control" required />
                        </div>
                        <div class="form-group">
	                    	<input type="submit" name="add_pdtn_stage_btn" value="Add Production Stage" class="btn-block btn btn-primary" />
                        </div>
                    </div>
                </form>
                <!-- /new-production-stage-pane -->

            <?php } elseif ($page_view=='manage-production-stages') { ?>

            	<flex>
                    <div class="col-sm-3 well well-lg" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header">Select production stage</h4>
                        <h4 ko="visible: stages_list().length == 0" class="text-muted text-center small">
                        	No production stages added yet
                        </h4>
                        <div ko="foreach: stages_list" class="small text-uppercase" style="font-weight: bold">
                        	<a class="no-decor btn-block" ko="click: $root.stage_selected" style="cursor:pointer; padding: .5rem 0">
                            	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: $root.is_selected( id )"></span>
                                <span ko="text: stage_name"></span>
                            </a>
                        </div>
                    </div>
	                <!-- /production-stages-list -->
                    <div class="col-sm-9 well well-sm" style="margin-top: 10px; border-left:none">
						<h4 ko="visible: !selected()" class="text-muted text-center small">
                        	Click on a production stage from the list on the left to manage
                        </h4>
                    	<form action="" method="post" style="display:none; background: #fff; padding: 1rem 2rem" ko="visible: selected, with: selected, submit: update_stage_form_submit">
                        	<input type="hidden" name="stage_id" ko="value: id" />
                            <div class="form-group col-sm-6">
                                <label class="control-label">Production stage name</label>
                                <input type="text" name="stage_name" placeholder="enter stage name" class="form-control" ko="value: stage_name, value_update: 'input'" required />
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="control-label">Production stage rank</label>
                                <input type="number" name="stage_rank" placeholder="enter stage rank" min="1" class="form-control" ko="value: stage_rank, value_update: 'input'" required />
                            </div>
                            <div class="container-fluid small">
                            	<a class="pull-right" ko="attr: { href: '?del-stage='+id }, click: $root.confirm_stage_deletion">Remove production stage</a>
                            </div>
                            <div style="padding-bottom: .5rem">
                            	<div class="col-sm-6" ko="visible: $root.processes().length != 0">
		                            <h5>Production processes</h5>
                                    <div ko="foreach: $root.processes" style="overflow: auto">
                                        <div class="small">
                                        	<input type="hidden" name="proc_ids[]" ko="value: uid" />
                                        	<input type="hidden" name="proc_names[]" ko="value: name" />
                                        	<input type="hidden" name="proc_rates[]" ko="value: rate" />
                                        	<input type="hidden" name="proc_units[]" ko="value: unit" />

                                            <div ko="visible: !editing()">
                                            <div class="pull-right">
                                                <a class="fa fa-fw fa-edit" style="cursor:pointer" ko="click: toggle_edit" title="Edit process"></a>
                                                <a class="fa fa-fw fa-remove" style="cursor:pointer" ko="click: remove" title="Remove process"></a>
                                           	</div>

                                        	<span class="pull-left" ko="text: $index()+1+'.', style: { paddingTop: editing()? '2rem':'0' }"></span>
                                        	<strong ko="text: name"></strong> &ensp;
                                            <em>(&#8358;<span ko="text: rate()+' per '+unit()"></span>)</em>
                                            </div>

                                            <div ko="visible: editing" style="padding: 1rem 0; margin: 3px 0; border: solid thin #ccc; border-width: 1px 0">
                                            <input class="form-control" style="width: 30%; display:inline-block; font-size: small" type="text" ko="value: name, value_update: 'input', textInput: name" />
                                            <input class="form-control" style="width: 30%; display:inline-block; font-size: small" type="number" ko="value: rate, value_update: 'input', textInput: rate" />
                                            <input class="form-control" style="width: 30%; display:inline-block; font-size: small" type="text" ko="value: unit, value_update: 'input', textInput: unit" />
                                            <a class="fa fa-fw fa-remove" style="cursor:pointer" ko="click: toggle_edit" title="Close"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6" ko="with: $root.proc">
                                	<h5>Add production process</h5>
                                    <div class="form-group" style="margin:0">
                                        <input type="text" placeholder="Process name" id="proc_name" class="form-control input-sm" ko="value: name, value_update: 'input', textInput: name" />
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <input type="number" placeholder="Process rate (&#8358;)" class="form-control input-sm add-proc" min="0" ko="value: rate, value_update: 'input', textInput: rate" />
                                    </div>
                                    <div class="form-group" style="margin:0">
                                        <input type="text" placeholder="Process unit e.g. page" class="form-control input-sm add-proc" ko="value: unit, value_update: 'input', textInput: unit" />
                                    </div>
                                    <div class="form-group">
                                        <a class="btn btn-sm btn-primary btn-block" ko="click: $root.add_process">Add process</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group text-center">
                            	<input type="submit" value="Update" name="update_pdtn_stage_btn" class="btn btn-default" />
                            </div>
                        </form>
                    </div>
	                <!-- /production-stages-details -->
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
