<?php
$title = "Shopping Cart";
require_once '../header.php';

if (Cart::get()->getItems() > 0) {

	$itemIDs = Cart::get()->getItems();
	$items = [];

	foreach ($itemIDs as $itemID) {
		$items[] = ['item' => new Item($itemID['item']->getItemID()), 'qty' => $itemID['qty']];
	}
}
?>

<div class="encapsulate_content">
	<h2><?php echo $title ?></h2>
	<?php foreach ($items as $item) { ?>
		<div class="cartItem">
			<div class="cartImage" style="background-image: url(<?php echo '../item-images/' . $item['item']->getItemImage() ?>);">
			</div>
			<div class="cartData">
				<div class="cartTop">
					<h3><?php echo $item['item']->getItemName(); ?></h3>
					<h4><?php echo $item['item']->getItemDescription(); ?></h4>
				</div>
				<div class="cartBottom">
					<p>(<?php echo $item['qty'] ?>)</p>
					<p>R <?php echo ($item['item']->getNormalPrice()) * $item['qty'] ?></p>
					<a class="button" style="background-color: rgba(255,192,0,1);" href="../site-engines/cart_engine.php?removeItem=<?php echo $item['item']->getItemID(); ?>&page=cart">Remove</a>
					<a class="button" style="background-color: rgba(0,192,255,1);" href="../site-engines/cart_engine.php?removeAllItems=<?php echo $item['item']->getItemID(); ?>&page=cart&qty=<?php echo $item['qty'] ?>">Remove All</a>
				</div>
			</div>
		</div>
	<?php } ?>
	<a class="button" style="background-color: rgba(192,255,0,1); float: right;" href="../site-pages/checkout.php">Checkout</a>
</div>

<?php
require_once '../footer.php';
?>