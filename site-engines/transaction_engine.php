<?php require_once '../autoloader.php';

/**
 * Do the transaction when the user confirms on the checkout screen.
 */
if (isset($_POST['buy'])) {
	$transaction = new Transaction();
	$transaction->doTransactionOnID(SiteUser::get()->getUserID(), $_POST['status']);
	Cart::destroy();
	header('Location:../site-pages/checkout.php?success="yes"');
}
?>