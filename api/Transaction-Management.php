<?php require_once '../autoloader.php';

if(isset($_POST['transaction'])) {
	$split = explode(",", $_POST['transaction']);
	
	$userID = $split[0];
	
	$items = [];
	for($a = 1; $a < (count($split) / 2); $a++) {
		$items[] = new Item($split[$a]);		
	}
	
	$qty = [];
	for($b = count($split / 2); $b < count($split); $b++) {
		$qty[] = $split[$b];
	}
	
	$status = $split[count($split) - 1];
	
	$transaction = new Transaction();
	$success = $transaction->doTransactionWithItems($userID, $items, $status, $qty);
	
	echo '<?xml version="1.0"?>';
	echo '<transaction>';
	echo '<success>'.$success.'</success>';
	echo '<item>'.$items[0]->getItemID().'</item>';
	echo '</transaction>';
}
?>