<?php

	include('lib/db.php');
	# !-Link up to db

	if (!$logged || !permission_manage_inventory() || !isset($_GET['page_view'])) {
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

	if (isset($_POST['add_inventory_item_btn'])) {
		if (inventory_item_exists($_POST['item_name'], $_POST['item_cat'])) {
			js_alert("Inventory item exists already", ALERT_TYPE_FAILED);
		} else {
			add_inventory_item($_POST);
			js_alert("Item added to inventory successfully", ALERT_TYPE_SUCCESS);
		}
	}
	# !-Handle `new inventory item` form data

	if (isset($_POST['update_inventory_item_btn'])) {
		if (!inventory_item_exists($_POST['orig_item_name'], $_POST['orig_item_cat'])) {
			js_alert("Item not found in Inventory", ALERT_TYPE_FAILED);
		} else {
			update_inventory_item($_POST);
			js_alert("Item details updated successfully", ALERT_TYPE_SUCCESS);
		}
	}
	# !-Handle `update inventory item` form data

	if (isset($_GET['del-inventory-item'])) {
		remove_inventory_item($_GET['del-inventory-item']);
		header("REFRESH:0;URL=manage-inventory");
		die();
	}
	# !-Handle `remove inventory item` form data

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

    <title>Charvid Digital Press - Managing Inventory</title>

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
            <?php if ($page_view=='add-inventory-item') { ?>

                <form action="" method="post" class="text-muted" ko="submit: add_item_form_submit">
                    <div class="col-sm-4 col-sm-offset-4 well well-lg" style="margin-top: 10px">
                        <h4 class="page-header">Add item to inventory</h4>
                        <div class="form-group">
                            <label class="control-label">Item name</label>
                            <input type="text" name="item_name" placeholder="enter item name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Inventory category</label>
                            <select ko="visible: categories().length != 0, value: selected, attr: { name: !is_new_cat()? 'item_cat':'' }" class="form-control">
                            	<option style="display: none" value="">Select or add a category</option>
                                <!-- ko foreach: categories -->
                                <option ko="value: $data, text: $data"></option>
                                <!-- /ko -->
                            	<option value="__">Add a new category</option>
                            </select>
                            <input ko="visible: is_new_cat, value: item_cat, value_update: 'input', attr: { name: is_new_cat()? 'item_cat':'' }" class="form-control" type="text" placeholder="enter new category name" />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Unit price</label>
                            <input type="number" name="unit_price" placeholder="enter item unit price" min="1" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Stock <em>(available quantity)</em></label>
                            <input type="number" name="stock" placeholder="enter item stock" min="1" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label class="control-label">Threshold stock <em>(minimum stock)</em></label>
                            <input type="number" name="threshold" placeholder="enter threshold stock" min="1" class="form-control" required />
                        </div>
                        <div class="form-group">
	                    	<input type="submit" name="add_inventory_item_btn" value="Add Item" class="btn-block btn btn-primary" />
                        </div>
                    </div>
                </form>
                <!-- /new-production-stage-pane -->

            <?php } elseif ($page_view=='manage-inventory') { ?>

            	<flex>
                    <div class="col-sm-3 well well-lg" style="margin-top: 10px; border-right:none">
                        <h4 class="page-header">Inventory items</h4>
                        <!-- ko foreach: inventory_items -->
                        	<a data-toggle="collapse" class="btn-block" ko="attr: { href: '#'+spaceToUnderscore(cat_name) }">
                            	<span ko="visible: $root.is_cat_selected( cat_name )">&bull;</span>
                                <span ko="text: cat_name"></span>
                            </a>
                            <div ko="attr: { id: spaceToUnderscore(cat_name) }, foreach: cat_items" class="collapse" style="padding: 5px; font-weight: bold; font-size: 85%">
                            	<a class="btn-block" style="color: #555" href="#" ko="click: $root.item_selected">
                                	<span class="animated flipInY fa fa-fw fa-hand-o-right" ko="visible: $root.is_selected( id )"></span>
                                	<span ko="text: item_name"></span>
                                </a>
                            </div>
                        <!-- /ko -->
                    </div>
	                <!-- /inventory-items-list -->
                    <div class="col-sm-9 well well-sm" style="margin-top: 10px; border-left:none">
						<h4 ko="visible: !selected()" class="text-muted text-center small">
                        	Click on an item from the list on the left to manage.<br />Click on an inventory category to expand or collapse its content
                        </h4>
                        <form action="" method="post" class="text-muted" ko="visible: selected, with: selected, submit: data_submitted">
                            <div style="margin-top: 10px">
                                <h4 class="page-header">Edit item details</h4>
                                <div class="form-group">
                                    <label class="control-label">Item name</label>
                                    <input type="text" name="item_name" ko="value: item_name" placeholder="enter item name" class="form-control" required />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Inventory category</label>
                                    <select ko="visible: $root.inv_cats().length != 0, value: $root.selected_cat, attr: { name: !$root.is_new_cat()? 'item_cat':'' }" class="form-control">
                                        <option style="display: none" value="">Select or add a category</option>
                                        <!-- ko foreach: $root.inv_cats -->
                                        <option ko="value: $data, text: $data"></option>
                                        <!-- /ko -->
                                        <option value="__">Add a new category</option>
                                    </select>
                                    <input ko="visible: $root.is_new_cat, value: $root.item_cat, value_update: 'input', attr: { name: $root.is_new_cat()? 'item_cat':'' }" class="form-control" type="text" placeholder="enter new category name" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Unit price</label>
                                    <input type="number" name="unit_price" ko="value: unit_price" placeholder="enter item unit price" min="1" class="form-control" required />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Stock <em>(available quantity)</em></label>
                                    <input type="number" name="stock" ko="value: stock" placeholder="enter item stock" min="0" class="form-control" required />
                                </div>
                                <div class="form-group" ko="visible: $root.stock_changed">
                                    <label class="control-label">Purpose <em>(reason for change in quantity)</em></label>
                                    <input name="purpose" ko="value: $root.purpose, value_update: 'input'" type="text" placeholder="enter reason for change in quantity" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Threshold stock <em>(minimum stock)</em></label>
                                    <input type="number" name="threshold" ko="value: threshold" placeholder="enter threshold stock" min="1" class="form-control" required />
                                </div>
                                <div class="form-group small">
                            		<a class="pull-right" ko="attr: { href: '?del-inventory-item='+id }, click: $root.confirm_item_deletion">Remove item from inventory</a>
                                    <div class="clearfix"></div>
	                            </div>
                                <div class="form-group">
                                	<input type="hidden" name="id" ko="value: id" />
                                	<input type="hidden" name="orig_item_name" ko="value: item_name" />
                                	<input type="hidden" name="orig_item_cat" ko="value: item_cat" />
                                	<input type="hidden" name="orig_unit_price" ko="value: unit_price" />
                                	<input type="hidden" name="orig_stock" ko="value: stock" />
                                	<input type="hidden" name="orig_threshold" ko="value: threshold" />
                                    <input type="submit" name="update_inventory_item_btn" value="Update details" class="btn-block btn btn-primary" />
                                </div>
                            </div>
                        </form>
                    </div>
	                <!-- /inventory-items-details -->
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
