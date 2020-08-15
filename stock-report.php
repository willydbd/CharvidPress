<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_inventory_notification() || !isset($_GET['page_view'])) {
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

    <title>Charvid Digital Press - Inventory Stock Report</title>

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
            <?php if ($page_view=='almost-finished') {
				$items = get_threshold_items();
				$cats = array();
				foreach ($items as $item)
					$cats[$item['item_cat']][] = $item;
					# Group by categories
			?>

                <h4>Supply is running low for the following items in the inventory</h4>
                <br />
                <?php foreach ($cats as $cat=>$items) {
					$id = rand();
					$i = 0;
					?>

                	<a data-toggle="collapse" href="#<?php echo $id ?>">
                    	<i class="fa fa-fw fa-chevron-down"></i>
						<?php echo $cat.' ('.count($items).')' ?>
                       	</a><div class="clearfix"></div>

                    <div id="<?php echo $id ?>" class="collapse in" style="padding: 5px">
                    	<table class="table table-responsive table-striped table-bordered">
                        	<tr>
                            	<th>S|N</th>
                                <th>Name</th>
                                <th>Stock</th>
                            </tr>
						<?php foreach ($items as $item) { extract($item); $i++; ?>
                            <tr>
                            	<td><?php echo $i.'.' ?></td>
                            	<td><?php echo $item_name ?></td>
                            	<td><?php echo $stock ?></td>
                            </tr>
                        <?php } ?>
                    	</table>
                    </div>
                <?php } ?>

           	<?php } elseif ($page_view=='out-of-stock') {
				$items = get_out_of_stock_items();
				$cats = array();
				foreach ($items as $item)
					$cats[$item['item_cat']][] = $item;
					# Group by categories
			?>

                <h4>The following items are out of stock in the inventory</h4>
                <br />
                <?php foreach ($cats as $cat=>$items) {
					$id = rand();
					$i = 0;
					?>

                	<a data-toggle="collapse" href="#<?php echo $id ?>">
                    	<i class="fa fa-fw fa-chevron-down"></i>
						<?php echo $cat.' ('.count($items).')' ?>
                       	</a><div class="clearfix"></div>

                    <div id="<?php echo $id ?>" class="collapse in" style="padding: 5px">
                    	<table class="table table-responsive table-striped table-bordered">
                        	<tr style="background:#f04124; color: white">
                            	<th>S|N</th>
                                <th>Name</th>
                                <th>Stock</th>
                            </tr>
						<?php foreach ($items as $item) { extract($item); $i++; ?>
                            <tr>
                            	<td><?php echo $i.'.' ?></td>
                            	<td><?php echo $item_name ?></td>
                            	<td><?php echo $stock ?></td>
                            </tr>
                        <?php } ?>
                    	</table>
                    </div>
                <?php } ?>

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
