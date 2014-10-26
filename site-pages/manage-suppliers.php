<?php
$title = 'Manage Suppliers';
require_once '../header.php';

if (SiteUser::get() === false) {
	Cart::get()->setError('This page is not accessible to the public');
	header('Location:../index.php');
}

if (!SiteUser::get()->isAdmin()) {
	Cart::get()->setError('You do not have the required rights to access the page');
	header('Location:../index.php');
}
?>

<div class="encapsulate_content">
	<h2>Manage Suppliers</h2>
	<p>Here you can manage suppliers.</p>
	<br>
	<form name="edit-supplier" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-engines/supplier_engine.php">
		<input type="submit" name="delete" value="Delete" class="delete">
		<?php
		tm\SQLTableModel::createChecked('Supplier ID', 'SELECT
					supplierID AS \'Supplier ID\',
					supplierName AS \'Name\',
					telephone AS \'Telephone\',
					cellphone AS \'Cellphone\',
					address AS \'Address\',
					email AS \'Email\',
					description AS \'Description\',
					CONCAT("<input type=\"button\" class=\"submit\" value=\"Edit\" onclick=\" window.location=\'add-supplier.php?edit=true&id=",supplierID,"\'; \">") AS \'Edit\'
					FROM tblSuppliers',
				'ONLINE_SHOP', array(), 'supplierName', 50, false);
		?>
	</form>
</div>

<?php
require_once '../footer.php';
?>