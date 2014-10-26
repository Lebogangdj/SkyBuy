<?php
$title = 'Add Supplier';
require_once '../header.php';

if (SiteUser::get() === false) {
	Cart::get()->setError('This page is not accessible to the public');
	header('Location:../index.php');
}

if (!SiteUser::get()->isAdmin()) {
	Cart::get()->setError('You do not have the required rights to access the page');
	header('Location:../index.php');
}

$supplierName = "";
$telephone = "";
$cellphone = "";
$address = "";
$email = "";
$description = "";
$submitText = 'Add Supplier';
$buttonAction = 'add';

if (isset($_GET['edit'])) {
	$title = 'Edit Supplier';

	$supplier = new Supplier();
	$supplier->fillSupplierByID($_GET['id']);

	$submitText = 'Edit Supplier';
	$buttonAction = 'update';

	$supplierID = $_GET['id'];
	$supplierName = $supplier->getSupplierName();
	$telephone = $supplier->getTelephone();
	$cellphone = $supplier->getCellphone();
	$address = $supplier->getAddress();
	$email = $supplier->getEmail();
	$description = $supplier->getDescription();
}
?>

<div class="encapsulate_content">
	<h2><?php echo $title; ?></h2>
	<form name="add-supplier" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-engines/supplier_engine.php">
		<table style="margin-top: 20px; margin-bottom: 15px;">
			<tr>
				<td><label>Supplier Name</label></td>
				<td><input type="text" placeholder="Supplier Name" name="supplierName" <?php echo 'value="' . $supplierName . '"'; ?>></td>
			</tr>
			<tr>
				<td><label>Telephone</label></td>
				<td><input type="text" name="telephone" placeholder="000 123 4568" <?php echo 'value="' . $telephone . '"'; ?>></td>
			</tr>
			<tr>
				<td><label>Cellphone</label></td>
				<td><input type="text" name="cellphone" placeholder="111 865 5145" <?php echo 'value="' . $cellphone . '"'; ?>></td>
			</tr>
			<tr>
				<td><label>Address</label></td>
				<td><input type="text" name="address" placeholder="31 Somewhere Lane, City, Postal Code" <?php echo 'value="' . $address . '"'; ?>></td>
			</tr>
			<tr>
				<td><label>Email</label></td>
				<td><input type="text" name="email" placeholder="supplier@somewhere.com" <?php echo 'value="' . $email . '"'; ?></td>
			</tr>
			<tr>
				<td><label>Description</label></td>
				<td><textarea name="description" placeholder="Some notes on the supplier."><?php echo $description; ?></textarea></td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="<?php echo $buttonAction; ?>" value="<?php echo $submitText; ?>" class="submit"> 
				</td>
			</tr>
			<?php if (isset($_GET['edit'])) { ?>
				<input type='hidden' name='supplierID' value="<?php echo $supplierID; ?>">
			<?php } ?>
		</table>
	</form>
</div>

<?php
require_once '../footer.php';
?>