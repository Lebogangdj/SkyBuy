<?php
$title = 'Checkout';
require_once '../header.php';

if (Cart::get()->getItems() > 0) {
	$itemIDs = Cart::get()->getItems();
	$totalCost = 0;
}

if (isset($_GET['success'])) {
	$title = 'Success';
}
?>

<div class="encapsulate_content">
	<?php if (!isset($_GET['success'])) { ?>
		<h2><?php echo $title; ?></h2>
		<form name="checkout" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR'] ?>/site-engines/transaction_engine.php">
			<div class="checkOutBox">
				<div class="topWrap" style="border-bottom: 1px solid rgba(0,0,0,0.2); ">
					<div class="checkOutName">
						<p>Item Name</p>
					</div>
					<div class="checkOutDescrip">
						<p>Description</p>
					</div>
					<div class="checkOutItemCount">
						<p>Amount</p>
					</div>
					<div class="checkOutSubtotal">
						<p>Cost</p>
					</div>
				</div>
				<?php foreach ($itemIDs as $item) { ?>
					<div class="topWrap">
						<div class="checkOutName">
							<p><?php echo $item['item']->getItemName(); ?></p>
						</div>
						<div class="checkOutDescrip">
							<p><?php echo $item['item']->getItemDescription(true); ?></p>
						</div>
						<div class="checkOutItemCount">
							<p><?php echo $item['qty']; ?></p>
						</div>
						<div class="checkOutSubtotal">
							<p>R <?php echo Cart::get()->getSubTotalbyID($item['item']->getItemID()); ?></p>
						</div>
					</div>
				<?php } ?>
				<div class="bottomWrap" style="border-bottom: 1px solid rgba(0,0,0,0.2); border-top: 1px solid rgba(0,0,0,0.2); ">
					<div class="checkOutName"></div>
					<div class="checkOutDescrip"></div>
					<div class="checkOutItemCount">
						<p>Total</p>
					</div>
					<div class="checkOutSubtotal">
						<p>Total</p>
					</div>					
				</div>
				<div class="bottomWrap">
					<div class="checkOutName"></div>
					<div class="checkOutDescrip"></div>
					<div class="checkOutItemCount">
						<p><?php echo Cart::get()->getTotalQunatity(); ?></p>
					</div>
					<div class="checkOutSubtotal">
						<p>R <?php echo Cart::get()->getTotal(); ?></p>
					</div>					
				</div>
			</div>
			<?php if (SiteUser::get()) { ?>
				<input type="submit" name="buy" value="Purchase" class="submit" style="float: right; margin-top: 10px;" />
				<div class="selectStyle" style="float: right; margin-top: 10px; margin-right: 10px;">
					<select name="status">
						<option value="COLLECTION">Collection</option>
						<option value="DELIVERY">Delivery</option>
					</select>
				</div>
			<?php } else { ?>
				<a class="button" style="float: right;" href="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-pages/create-user.php">Create User Account</a>
			<?php } ?>
		</form>
	<?php } else { ?>
		<h2><?php echo $title; ?></h2>
		<p>Your purchase was successfully made.</p>
	<?php } ?>
</div>

<?php
require_once '../footer.php';
?>