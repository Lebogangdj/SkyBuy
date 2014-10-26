<?php

require_once '../autoloader.php';

// Specifies which input fields to validate.
$inputFields = array($_POST['itemName'], $_POST['itemDescription'], $_POST['itemTags'], $_POST['normalPrice'], $_POST['costPrice'], $_POST['hardStock']);

if (isset($_POST['update'])) {
	$item = new Item($_POST['itemID']);
	$softStock = (int) $_POST['hardStock'] - ((int) $item->getHardStock() - (int) $item->getSoftStock());

	if (Cart::get()->checkItemFields($inputFields)) {
		if ($_POST['hardStock'] >= $item->getHardStock()) {
			if ($_POST['normalPrice'] > $_POST['costPrice']) {
				$item->setItemName($_POST['itemName']);
				$item->setHardStock($_POST['hardStock']);
				$item->setSoftStock($softStock);
				$item->setItemDescription($_POST['itemDescription']);
				if ($_FILES['itemPicture']['error'] == 0) {
					processItemImage($item->getItemImage());
				}
				$item->setCostPrice($_POST['costPrice']);
				$item->setNormalPrice($_POST['normalPrice']);
				$item->addTags(processTags());
				$item->updateItemById();

				header('Location:../site-pages/manage-items.php');
			} else {
				Cart::get()->setError('Selling price must be higher than cost price');
				header('Location:../site-pages/add-item.php?edit=yes&id=' . $_POST['itemID']);
			}
		} else {
			Cart::get()->setError('The Hard Stock value entered must be more than the current value');
			header('Location:../site-pages/add-item.php?edit=yes&id=' . $_POST['itemID']);
		}
	} else {
		header('Location:../site-pages/add-item.php?edit=yes&id=' . $_POST['itemID']);
	}
}

/**
 * Removes the selected items from the database, by flagging them as inactive.
 */
if (isset($_POST['delete'])) {
	$results = \tm\Table::getPostModel()->getCheckedValues();

	foreach ($results AS $result) {
		$item = new Item($result);
		$item->markInactiveByID($result);
	}
	header('Location:../site-pages/manage-items.php');
}

/**
 * Adds the item to the database.
 */
if (isset($_POST['add'])) {
	if (Cart::get()->checkItemFields($inputFields)) {
		$item = new Item();
		$item->setItemName($_POST['itemName']);
		$item->setHardStock($_POST['hardStock']);
		$item->setSoftStock($_POST['hardStock']);
		$item->setItemDescription($_POST['itemDescription']);
		$item->setUserID(SiteUser::get()->getUserID());
		$item->insertItem();

		// Adds a price to the item.
		$item->addPrice($_POST['costPrice'], $_POST['normalPrice']);

		// Attaches the picture to the item.
		$imageID = $item->addImage();
		processItemImage($imageID);

		// Attaches suppliers to the item.
		$suppliers = tm\Table::getPostModel()->getCheckedValues();
		$item->addSupplier($suppliers);

		// Attaches tags to the item.
		$tags = processTags();
		$item->addTags($tags);

		header('Location:../site-pages/manage-items.php');
	} else {
		header('Location:../site-pages/add-item.php');
	}
}


/* ========== Extra Operations ========== */

/**
 * Process the image when uploaded by the user.
 */
function processItemImage($imageID) {
	if (file_exists(__DIR__ . '/../item-images/' . $imageID)) {
		unlink(__DIR__ . '/../item-images/' . $imageID);
	}
	move_uploaded_file($_FILES['itemPicture']['tmp_name'], __DIR__ . '/../item-images/' . $imageID);
}

/**
 * Process the tags that were given by the user.
 * @return type
 */
function processTags() {
	$tags = explode(',', $_POST['itemTags']);
	for ($count = 0; $count < sizeof($tags); $count++) {
		$tags[$count] = preg_replace('/\s+/', '', $tags[$count]);
	}
	return $tags;
}

?>