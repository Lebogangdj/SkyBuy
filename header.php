<?php
require_once(__DIR__ . '/autoloader.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-design/stylesheets/main.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<script src="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/table-system/table-javascript.js"></script>
		<script src="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-design/jquery2.min.js"></script>
		<link rel="icon" type="image/png" href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-design/images/favicon.png">
		<script>
			$(document).ready(function() {
				//hack to stop the animations from starting on page load
				$("body").removeClass("preload");

				// $(".subHeader").slideDown('slow');
			});


		</script>
    </head>
    <body class="clouds">
		<div id="layout">
			<div class="header">
				<div class="center_on_screen">
					<h1 id="pageTitle" class="preload"><a href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/index.php">Sky Buy</a></h1>
					<div class="menuBar whiteBar">
						<div><a href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-pages/list-items.php">Inventory</a></div>
						<?php if (count(Cart::get()->getItems()) > 0) { ?>
							<div><a href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-pages/cart.php">Cart (<?php echo Cart::get()->getTotalQunatity(); ?>)</a></div>
						<?php } ?>
					</div>
					<div class="login">
						<?php if (SiteUser::get() === false) { ?>
							<form name="login" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-engines/login.php">
								<input style="margin-right: 5px;" type="text" name="email" placeholder="email">
								<input style="margin-right: 5px;" type="password" name="password" placeholder="password">
								<input type="submit" name="submit" value="Login">
								<input class="loginButton" type="button" name="createUser" value="Create User" onclick="window.location.href = 'http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-pages/create-user.php'">
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
			if (SiteUser::get() !== false) {
				echo '<div class="subHeader header">
							<div class="center_on_screen">
								<span>' . SiteUser::get()->getFirstName() . ' ' . SiteUser::get()->getLastName() . '</span>
								<div class="menuBar blueBar">';
				if (SiteUser::get()->isAdmin()) {
					echo '<div class="dropdown">Admin
												<ul>
													<li><a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/add-item.php">Add Item</a></li>
													<li><a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/add-supplier.php">Add Supplier</a></li>
													<li><a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/manage-suppliers.php">Manage Suppliers</a></li>
													<li><a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/manage-items.php">Manage Items</a></li>
													<li><a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/manage-users.php">Manage Users</a></li>
												</ul>
											 </div>';
				}
				echo '<div>
										<a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-pages/purchases.php">Purchases</a>
									</div>
									<div>
										<a href="http://' . $_SERVER['SERVER_ADDR'] . '/site-engines/logout.php">Logout</a>
									</div>
								</div>
							</div>
					</div>';
			}
			if ($error = Cart::get()->hasError()) {
				echo '<div class="errorHeader header">
							<div class="center_on_screen">
								<span style="color: white !important;">
									' . $error . '
								</span>
							</div>
					</div>';
			}
			?>
			<div class="center_on_screen">