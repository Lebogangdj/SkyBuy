<?php
$title = 'Add Item';
require_once '../header.php';

if (SiteUser::get() === false) {
	Cart::get()->setError('This page is not accessible to the public');
	header('Location:../index.php');
}

if (!SiteUser::get()->isAdmin()) {
	Cart::get()->setError('You do not have the required rights to access the page');
	header('Location:../index.php');
}

$itemID = '';
$itemName = '';
$hardStock = '';
$itemDescription = '';
$itemPicture = '';
$costPrice = '';
$normalPrice = '';
$itemTags = '';
$submitText = 'Add Item';
$buttonAction = 'add';

if (isset($_GET['edit'])) {
	$title = 'Edit Item';

	$item = new Item($_GET['id']);

	$submitText = 'Edit Item';
	$buttonAction = 'update';

	$itemID = $_GET['id'];
	$itemName = $item->getItemName();
	$hardStock = $item->getHardStock();
	$itemDescription = $item->getItemDescription();
	$itemPicture = $item->getItemImage();
	$costPrice = $item->getCostPrice();
	$normalPrice = $item->getNormalPrice();
	$itemTags = $item->getAllTags();
}
?>

<script>
	$(function() {
		$('input[name=itemName]').focus(function() {
			$('label[name=nameDescrip]').fadeIn("fast");
		});

		$('input[name=itemName]').focusout(function() {
			$('label[name=nameDescrip]').fadeOut("fast");
		});

		$('input[name=hardStock]').focus(function() {
			$('label[name=stockDescrip]').fadeIn("fast");
		});

		$('input[name=hardStock]').focusout(function() {
			$('label[name=stockDescrip]').fadeOut("fast");
		});

		$('textarea[name=itemDescription]').focus(function() {
			$('label[name=itemDescrip]').fadeIn("fast");
		});

		$('textarea[name=itemDescription]').focusout(function() {
			$('label[name=itemDescrip]').fadeOut("fast");
		});

		$('input[name=costPrice]').focus(function() {
			$('label[name=costPriceDescrip]').fadeIn("fast");
		});

		$('input[name=costPrice]').focusout(function() {
			$('label[name=costPriceDescrip]').fadeOut("fast");
		});

		$('input[name=normalPrice]').focus(function() {
			$('label[name=normalPriceDescrip]').fadeIn("fast");
		});

		$('input[name=normalPrice]').focusout(function() {
			$('label[name=normalPriceDescrip]').fadeOut("fast");
		});

		$('input[name=itemTags]').focus(function() {
			$('label[name=tagDescrip]').fadeIn("fast");
		});

		$('input[name=itemTags]').focusout(function() {
			$('label[name=tagDescrip]').fadeOut("fast");
		});
	});

</script>

<div class="encapsulate_content">
	<h2><?php echo $title ?></h2>
	<form name="add-item" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR'] . '/site-engines/item_engine.php'; ?>" enctype="multipart/form-data">
		<div class="encapsulate_content" style="background-color: rgba(0,0,0,0.01);">
			<table style="margin-top: 20px;">
				<tr>
					<td><label>Item Name</label></td>
					<td><input placeholder="Apple Juice" type="text" name="itemName" value="<?php print_r($itemName); ?>" </td>
					<td><label name="nameDescrip" style="font-size: 10px; display: none;">The name of the item.</label></td>
				</tr>
				<tr>
					<td><label>Hard Stock</label></td>
					<td><input placeholder="10" type="text" name="hardStock" value="<?php echo $hardStock; ?>"/></td>
					<td><label name="stockDescrip" style="font-size: 10px; display: none;">The amount of physical stock on hand.</label></td>
				</tr>
				<?php if(isset($_GET['edit'])) { ?>
				<tr>
					<td><label>Soft Stock</label></td>
					<td><label><?php echo $item->getSoftStock(); ?></label></td>
				</tr>
				<?php } ?>
				<tr>
					<td><label>Item Description</label></td>
					<td><textarea placeholder="Wonderful tasting Apple Juice!" name="itemDescription"><?php echo $itemDescription; ?></textarea></td>
					<td><label name="itemDescrip" style="font-size: 10px; display: none;">A description of the item that appears beneath it when viewed.</label></td>
				</tr>
				<tr valign="top">
					<td><label>Picture</label></td>
					<td>
						<input type="file" name="itemPicture" />
						<br>
						<br>
						<?php if (strcmp($itemPicture, '')) { ?>
							<img src="<?php echo '../item-images/' . $itemPicture; ?>" style="max-height: 175px; border-radius: 5px; padding: 5px; background-color: rgba(0,0,0,0.2);" alt="Something" title="Current Picture">
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><label>Cost Price</label></td>
					<td><input placeholder="8.50" type="text" name="costPrice" value="<?php echo $costPrice; ?>"/></td>
					<td><label name="costPriceDescrip" style="font-size: 10px; display: none;">The cost price of the item from the supplier.</label></td>
				</tr>
				<tr>
					<td><label>Sell Price</label></td>
					<td><input placeholder="10.50" type="text" name="normalPrice" value="<?php echo $normalPrice; ?>"/></td>
					<td><label name="normalPriceDescrip" style="font-size: 10px; display: none;">The selling price of the item that the user will see.</label></td>
				</tr>
				<tr>
					<td><label>Tags</label></td>
					<td><input placeholder="graphics,food,unique" type="text" name="itemTags" value="<?php echo $itemTags; ?>"/></td>
					<td><label name="tagDescrip" style="font-size: 10px; display: none;">Tags that the user can search, related to this item.</label></td>
				</tr>
			</table>
		</div>
		<?php if (isset($_GET['edit'])) { ?>
			<input type='hidden' name='itemID' value="<?php echo $itemID; ?>">
		<?php } ?>
		<div class="encapsulate_content" style="background-color: rgba(0,0,0,0.01);">
			<label style="text-align: center;">Suppliers</label>
			<label style="font-size: 10px;">The supplier(s) of the item.</label>
			<?php tm\SQLTableModel::createChecked('supplierID', 'SELECT * FROM tblSuppliers', 'ONLINE_SHOP', array(), 'supplierName', 50, false); ?>
		</div>
		<table>
			<tr>
				<td><input type="submit" name="<?php echo $buttonAction; ?>" value="<?php echo $submitText; ?>" class="submit"/></td>
			</tr>
		</table>
	</form>
</div>

<?php
require_once '../footer.php';
?>