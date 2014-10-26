<?php
$title = 'Manage Users';
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
	<h2>Manage Users</h2>
	<p>Here you can manage users.</p>
	<br>
	<form name="edit-user" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-engines/user_engine.php">
		<input type="submit" name="delete" value="Delete" class="delete">
		<?php
		tm\SQLTableModel::createChecked('User ID', 'SELECT
						tblUsers.userID AS \'User ID\',
						tblUsers.firstName AS \'First Name\',
						tblUsers.lastName AS \'Last Name\',
						tblUsers.title AS \'Title\',
						tblUsers.email AS \'Email\',
						tblUsers.address AS \'Address\',
						tblRights.status AS \'Admin\',
						CONCAT("<input type=\"button\" class=\"submit\" value=\"Edit\" onclick=\" window.location=\'create-user.php?edit=true&id=",tblUsers.userID,"\'; \">") AS \'Edit\'
						FROM tblUsers
						
						LEFT OUTER JOIN tblRights
						ON tblUsers.userID = tblRights.userID', 'ONLINE_SHOP', array(), 'userName', 50, false);
		?>
	</form>
</div>

<?php
require_once '../footer.php';
?>