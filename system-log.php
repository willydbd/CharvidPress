<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_view_system_log() || !isset($_GET['page_view'])) {
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

    <title>Charvid Digital Press - System Log</title>

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

			<div class="well print-nopadding print-noborder" style="background: transparent; border-bottom:none; border-left:none; border-right:none">
            <?php if ($page_view=='system-log') { ?>

            	<h4 class=" no-print">Viewing System Log</h4>
                <flex>
                	<div class="col-sm-3 well well-lg no-print" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header">Log titles</h4>
                        <div ko="foreach: log_titles" class="small text-uppercase" style="font-weight: bold">
                        	<a class="no-decor btn-block" ko="click: $root.log_selected, attr: { title: tooltip }" style="cursor:pointer; padding: .5rem 0">
                            	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: $root.is_selected( label )"></span>
                                <span ko="text: title"></span>
                            </a>
                        </div>
                    </div>
	                <!-- /log-titles -->
                    <div class="col-sm-9 well well-sm print-noborder" style="margin-top: 10px; border-left:none">

						<h4 ko="visible: !selected()" class="text-muted text-center small">
                        	Click on a log title from the list on the left to access its content
                        </h4>
                        <div ko="visible: selected" class="print-nopadding print-noborder print-fullwidth" style="background: #fff; padding: 1rem 2rem">

                        	<div class="text-right small no-print" style="padding: 1rem 0rem">
                            	<i class="fa fa-spinner fa-fw fa-spin pull-left" ko="visible: busy"></i>
                                <span class="text-muted">Found records for <span ko="text: log_data().length"></span> day(s)</span> &emsp;
                        		<a href="#" ko="click: refresh_data">Refresh</a>
                            	</div>

                        	<h4 ko="visible: log_data().length == 0" class="text-muted text-center small">
                                No log data for selected title
                            </h4>
                        	<!-- ko foreach: log_data -->
                            <table class="table table-responsive" style="font-size: 85%; width: 100%">
                                <tr>
                                    <th data-toggle="collapse" class="text-uppercase text-center print-noborder" ko="attr: { href: '#'+spaceToUnderscore(day) }" style="background: #aaa; color: #fff; cursor: pointer">
                                    	<span ko="text: day"></span>
                                        <small class="pull-right" ko="text: content.length"></small>
                                    </th>
                                </tr>
                                <tr>
                                	<td style="padding:0" class="collapse" ko="attr: { id: spaceToUnderscore(day) }">
                                    	<table class="table table-responsive table-bordered table-striped" style="margin-bottom:0">
                                        <tbody>
                                        <!-- ko foreach: content -->
                                            <tr>
                                            	<td>
                                                	<strong>At <span ko="text: when"></span></strong>
                                                    <div style="padding: .5rem 0 0 1rem; font-size: 120%; line-height: 1.5; white-space:pre-wrap" class="text-justify" ko="text: report">
                                                    </div>
                                                </td>
                                            </tr>
                                        <!-- /ko -->
                                        </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <!-- /ko -->
                        </div>
                    </div>
                    <!-- /log-content -->
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
