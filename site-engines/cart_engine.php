<?php

require_once '../autoloader.php';

if (isset($_GET['cartItemID'])) {
	Cart::get()->addItemByID($_GET['cartItemID']);
	header('Location:../site-pages/' . $_GET['page'] . '.php?itemID=' . $_GET['cartItemID']);
}

if (isset($_GET['removeItem'])) {
	Cart::get()->removeItemByID($_GET['removeItem']);
	header('Location:../site-pages/' . $_GET['page'] . '.php');
}

if (isset($_GET['removeAllItems'])) {
	Cart::get()->removeItemByID($_GET['removeAllItems'], $_GET['qty']);
	header('Location:../site-pages/' . $_GET['page'] . '.php');
}
?>