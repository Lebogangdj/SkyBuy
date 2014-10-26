<?php
$title = 'Purchases';
require_once '../header.php';

$transactions = SiteUser::get()->getPastPurchases();
?>

<div class="encapsulate_content">
	<h2><?php echo $title; ?></h2>
	<?php foreach ($transactions as $transaction) { ?>
		<div class="checkOutBox">
			<div class="topWrap">
				<div class="title">
					<p><?php echo $transaction['info']['timestamp'].' - (Reference) '. $transaction['info']['reference'] ?></p>
				</div>
			</div>
			<div class="topWrap" style="border-bottom: 1px solid rgba(0,0,0,0.2); font-weight: bold;">
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
				<div class="checkOutSubtotal">
					<p>Total</p>
				</div>
			</div>
			<?php foreach ($transaction['items'] as $item) { ?>
				<div class="topWrap">
					<div class="checkOutName">
						<p><?php echo $item['itemName']; ?></p>
					</div>
					<div class="checkOutDescrip">
						<p><?php echo $item['itemDescription']; ?></p>
					</div>
					<div class="checkOutItemCount">
						<p><?php echo $item['quantity']; ?></p>
					</div>
					<div class="checkOutSubtotal">
						<p>R <?php echo $item['normalPrice']; ?></p>
					</div>
					<div class="checkOutSubtotal">
						<p>R <?php echo $item['normalPrice'] * $item['quantity']; ?></p>
					</div>
				</div>
			<?php } ?>
		</div>
		<br>
	<?php } ?>
</div>

<?php
require_once '../footer.php';
?>