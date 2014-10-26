<?php
$title = 'Manage Items';
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
	<h2>Manage Items</h2>
	<p>Here you can manage items.</p>
	<br>
	<form name="edit-item" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-engines/item_engine.php">
		<input type="submit" name="delete" value="Delete" class="delete">
		<?php
		\tm\SQLTableModel::createChecked('Item ID', 
				'SELECT 
					tblItems.itemID AS \'Item ID\', 
					tblItems.itemName AS \'Name\', 
					tblItems.itemDescription AS \'Description\', 
					CONCAT(tblUsers.firstName,\' \',tblUsers.lastName) AS \'Creator\',
					tblItems.hardStock AS \'Hard Stock\',
					tblItems.softStock AS \'Soft Stock\', 
					(
						SELECT GROUP_CONCAT(name SEPARATOR \', \')
						FROM tblTags

						INNER JOIN tblItemTags
						ON tblTags.tagID = tblItemTags.tagID

						WHERE tblItemTags.itemID = tblItems.itemID
					) AS \'Tags\', 
					tblPrice.costPrice AS \'Cost Price\',
					tblPrice.normalPrice AS \'Sell Price\',
					CONCAT("<input type=\"button\" class=\"submit\" value=\"Edit\" onclick=\" window.location=\'add-item.php?edit=true&id=",tblItems.itemID,"\'; \">") AS \'Edit\'

				FROM tblItems

				INNER JOIN tblPrice
				ON tblItems.itemID = tblPrice.itemID
				AND tblPrice.active = \'ACTIVE\'

				INNER JOIN tblUsers
				ON tblItems.userID = tblUsers.userID
				
				WHERE tblItems.active = \'ACTIVE\'', 'ONLINE_SHOP', array(), 'itemName', 50, false);
		?>
	</form>
</div>

<?php
require_once '../footer.php';
?>