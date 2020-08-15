<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_inventory_overview() || !isset($_GET['page_view'])) {
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
<link rel="icon" href="<? echo $x_link_prefix ?>images/icon.png" />

    <title>Charvid Digital Press - Inventory Overview</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo $x_link_prefix ?>css/yeti.bootstrap.3.3.5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $link_prefix ?>css/style.css" rel="stylesheet">

    <!-- SweetAlert CSS -->
    <link href="<?php echo $link_prefix ?>css/plugins/sweetalert.css" rel="stylesheet">

    <!-- Labelauty CSS -->
    <link href="<? echo $link_prefix ?>css/plugins/jquery-labelauty.css" rel="stylesheet">

    <!-- Custom Fonts : FontAwesome -->
    <link href="<? echo $x_link_prefix ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Animate CSS -->
    <link href="<? echo $x_link_prefix ?>css/animate.css" rel="stylesheet" type="text/css">

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
                <h4>
                	Inventory Overview
                    <span class="pull-right"><?php echo date("d-m-Y") ?></span>
                    </h4>
            <?php
				$inv = get_inventory_items();
				$lump_sum = $qty = 0; ?>

                <table class="table table-responsive table-striped table-bordered" >
                	<tr>
                        <th class="btn-primary disabled">Category</th>
                        <th class="btn-primary disabled">Stock</th>
                        <th class="btn-primary disabled">Worth (&#8358;)</th>
                    </tr>
				<?php foreach ($inv as $cat=>$items) {
                        $worth = $stock = 0;
                        foreach ($items as $item) {
							$worth += $item['unit_price']*$item['stock'];
							$stock += $item['stock'];
						}
						$lump_sum += $worth;
						$qty += $stock ?>
                     <tr>
                     	<td><?php echo $cat ?></td>
                     	<td class="text-right"><strong><?php echo $stock ?></strong></td>
                     	<td class="text-right"><strong><?php echo number_format($worth,2,'.',',') ?></strong></td>
                     </tr>
                <?php foreach ($items as $item) {
					extract($item); ?>
                     <tr>
                     	<td class="text-right"><?php echo $item_name ?></td>
                     	<td class="text-center"><?php echo $stock ?></td>
                     	<td><? echo number_format($stock * $unit_price,2,'.',',') ?></td>
                     </tr>
				<?php } ?>
			<?php } ?>
                	<tr>
                    	<th>Total</th>
                        <th class="text-right"><?php echo number_format($qty,2,'.',',') ?></th>
                        <th class="text-right"><?php echo number_format($lump_sum,2,'.',',') ?></th>
                    </tr>
            	</table>
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
