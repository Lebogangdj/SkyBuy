<?php

require_once '../autoloader.php';

$inputFields = array($_POST['firstName'], $_POST['lastName'], $_POST['address'], $_POST['password']);

if (isset($_POST['add'])) {
	if (Cart::get()->checkInputFields($_POST['email'], null, null, $inputFields)) {
		$user = new User();

		$user->setFirstName($_POST['firstName']);
		$user->setLastName($_POST['lastName']);
		$user->setTitle($_POST['title']);
		$user->setEmail($_POST['email']);
		$user->setAddress($_POST['address']);
		$user->setPassword($_POST['password']);

		header('Location:../index.php');
		try {
			$user->insertCurrentUser();
		} catch (Exception $e) {
			Cart::get()->setError('The email already exists on the system');
			header('Location:../site-pages/create-user.php');
		}
	} else {
		header('Location:../site-pages/create-user.php');
	}
}

if (isset($_POST['update'])) {
	if (Cart::get()->checkInputFields($_POST['email'], null, null, $inputFields)) {
		$user = new User($_POST['userID']);

		$user->setFirstName($_POST['firstName']);
		$user->setLastName($_POST['lastName']);
		$user->setEmail($_POST['email']);
		$user->setAddress($_POST['address']);

		header('Location:../site-pages/manage-users.php');
		try {
			$user->updateCurrentuser($_POST['admin']);
		} catch (Exception $e) {
			Cart::get()->setError('The email already exists on the system');
		}
	} else {
		header('Location:../site-pages/create-user.php?edit=yes&id=' . $_POST['userID']);
	}
}

if (isset($_POST['delete'])) {
	$results = \tm\Table::getPostModel()->getCheckedValues();
	$user = new User();

	foreach ($results AS $result) {
		$user->deleteUserByID($result);
	}
	header('Location:../site-pages/manage-users.php');
}
?>