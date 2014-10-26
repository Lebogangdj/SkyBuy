<?php
$title = 'View Item';
require_once '../header.php';

$itemName = 'No Item';
$itemDescription = 'The item you are looking for cannot be found in this server.';
$price = '1 Million';

if (isset($_GET['itemID'])) {
	$item = new Item($_GET['itemID']);

	$itemID = $item->getItemID();
	$itemName = $item->getItemName();
	$itemDescription = $item->getItemDescription();
	$price = $item->getNormalPrice();
	$image = $item->getItemImage();
}
?>

<div class="encapsulate_content">
	<h2><?php echo $itemName ?></h2>
	<div class="viewItem">
		<div class="viewImage" style="background-image: url(<?php echo '../item-images/' . $image ?>);">
		</div>
		<div class="viewData">
			<div class="dataTop">
				<h4><?php echo $itemDescription ?></h4>
			</div>
			<div class="dataBottom">
				<p>R <?php echo $price ?></p>
				<a class="button" style="background-color: rgba(192,255,0,0.8);" href="../site-engines/cart_engine.php?cartItemID=<?php echo $itemID; ?>&page=view-item">Add To Cart</a>
				<a class="button" href="../site-pages/list-items.php">Back</a>
			</div>
		</div>
	</div>
</div>

<?php
require_once '../footer.php';
?>