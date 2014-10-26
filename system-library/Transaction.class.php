<?php

class Transaction {

	function __construct() {
	}
	
	function doTransactionWithItems($userID, $items, $status, $qty) {
		$total = 0;
		for($a = 0; $a < count($items); $a++) {
			$total += $items[a]->getNormalPrice() * $qty[a];
		}
		
		$transactionID = PSQL::insert(
				'INSERT INTO tblTransactions 
				(userID, reference, total, status) 
				
				VALUES (?, ?, ?, ?)',
				'ONLINE_SHOP', array($userID, uniqid(), $total, $status));

		for($a = 0; $a < count($items); $a++) {
			PSQL::insert(
					'INSERT INTO tblTransactionItems 
					(transactionID, itemID, priceID, quantity)
					
					VALUES (?, ?, ?, ?)',
					'ONLINE_SHOP', array($transactionID, $item[a]->getItemID(), $item[a]->getPriceID(), $qty[a]));
		}
		
		return true;
	}

	function doTransactionOnID($userID, $status) {
		$items = Cart::get()->getItems();

		$transactionID = PSQL::insert(
				'INSERT INTO tblTransactions 
				(userID, reference, total, status) 
				
				VALUES (?, ?, ?, ?)',
				'ONLINE_SHOP', array($userID, uniqid(), Cart::get()->getTotal(), $status));

		foreach ($items as $item) {
			PSQL::insert(
					'INSERT INTO tblTransactionItems 
					(transactionID, itemID, priceID, quantity)
					
					VALUES (?, ?, ?, ?)',
					'ONLINE_SHOP', array($transactionID, $item['item']->getItemID(), $item['item']->getPriceID(), $item['qty']));
		}
	}

}

?>