<?php
$title = 'Create User';
require_once '../header.php';

$firstName = '';
$lastName = '';
$email = '';
$address = '';
$password = '';
$submitText = 'Add User';
$buttonAction = 'add';

if (isset($_GET['edit'])) {
	$title = 'Edit User';

	$user = new User($_GET['id']);
	$submitText = 'Edit User';
	$buttonAction = 'update';

	$userID = $user->getUserID();
	$firstName = $user->getFirstName();
	$lastName = $user->getLastName();
	$email = $user->getEmail();
	$address = $user->getAddress();
	$rights = $user->getRights();
}
?>

<div class="encapsulate_content">
	<h2><?php echo $title; ?></h2>
	<form name="create-user" method="post" action="http://<?php echo $_SERVER['SERVER_ADDR'] . '/site-engines/user_engine.php'; ?>">
		<div class="encapsulate_content" style="background-color: rgba(0,0,0,0.01);">
			<table cellspacing="10">
				<tr>
					<td><label>First Name</label></td>
					<td><input placeholder="John" type="text" name="firstName" value="<?php echo $firstName; ?>"></td>
				</tr>
				<tr>
					<td><label>Last Name</label></td>
					<td><input placeholder="Appleseed" type="text" name="lastName" value="<?php echo $lastName; ?>"></td>
				</tr>
				<?php if (!isset($_GET['edit'])) { ?>
					<tr>
						<td><label>Title</label></td>
						<td>
							<div class="selectStyle">
								<select name="title">
									<option value="MR">MR</option>
									<option value="MS">MS</option>
								</select>
							</div>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td><label>Email</label></td>
					<td><input placeholder="lucky@somewhere.com" type="text" name="email" value="<?php echo $email; ?>"></td>
				</tr>
				<tr>
					<td><label>Address</label></td>
					<td><input placeholder="31 Somewhere Lane, City, Postal Code" type="text" name="address" value="<?php echo $address; ?>"></td>
				</tr>
				<tr>
					<td><label>Password</label></td>
					<td><input placeholder="password" type="password" name="password" ></td>
				</tr>
				<?php if (isset($_GET['edit'])) { ?>
					<tr>
						<td><label>Admin</label></td>
						<?php if ($rights !== null) { ?>
							<td>
								<input type="radio" name="admin" value="yes" checked> Yes
								<input type="radio" style="margin-left: 20px;" name="admin" value="no"> No
							</td>
						<?php } ?>
						<?php if ($rights === null) { ?>
							<td>
								<input type="radio" name="admin" value="yes"> Yes
								<input type="radio" style="margin-left: 20px;" name="admin" value="no" checked> No
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
				<tr>
					<td><input class="submit" type="submit" value="<?php echo $submitText; ?>" name="<?php echo $buttonAction; ?>"></td>
				</tr>
			</table>
		</div>
		<?php if (isset($_GET['edit'])) { ?>
			<input type='hidden' name='userID' value="<?php echo $userID; ?>">
		<?php } ?>
	</form>
</div>

<?php
require_once '../footer.php';
?>