<?php

require_once '../autoloader.php';

$inputFields = array($_POST['supplierName'], $_POST['cellphone'], $_POST['telephone'], $_POST['address'], $_POST['address']);

/**
 * Deletes selected suppliers from the database.
 */
if (isset($_POST['delete'])) {
	$results = \tm\Table::getPostModel()->getCheckedValues();
	$supplier = new Supplier();

	foreach ($results AS $result) {
		$supplier->deleteSupplierByID($result);
	}
	header('Location:../site-pages/manage-suppliers.php');
}

/**
 * Updates a supplier in the database with new values.
 */
if (isset($_POST['update'])) {
	if (Cart::get()->checkInputFields($_POST['email'], $_POST['cellphone'], $_POST['telephone'], null, $inputFields)) {
		$supplier = new Supplier();

		$supplier->setSupplierName($_POST['supplierName']);
		$supplier->setTelephone($_POST['telephone']);
		$supplier->setCellphone($_POST['cellphone']);
		$supplier->setAddress($_POST['address']);
		$supplier->setEmail($_POST['email']);
		$supplier->setDescription($_POST['description']);

		$supplier->updateSupplierByID($_POST['supplierID']);
		header('Location:../site-pages/manage-suppliers.php');
	} else {
		header('Location:../site-pages/add-supplier.php?edit=yes&id=' . $_POST['supplierID']);
	}
}

/**
 * Adds a completely new supplier to the database.
 */
if (isset($_POST['add'])) {
	if (Cart::get()->checkInputFields($_POST['email'], $_POST['cellphone'], $_POST['telephone'], null, $inputFields)) {
		$supplier = new Supplier();

		$supplier->setSupplierName($_POST['supplierName']);
		$supplier->setTelephone($_POST['telephone']);
		$supplier->setCellphone($_POST['cellphone']);
		$supplier->setAddress($_POST['address']);
		$supplier->setEmail($_POST['email']);
		$supplier->setDescription($_POST['description']);

		$supplier->insertCurrentSupplier();
		header('Location:../site-pages/manage-suppliers.php');
	} else {
		header('Location:../site-pages/add-supplier.php');
	}
}
?>