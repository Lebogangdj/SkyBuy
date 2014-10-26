<?php

class Cart {

	private $items;
	private $search;
	private $error;

	function __construct() {
		$this->items = [];
	}

	function addItemByID($itemID, $quantity = 1) {
		// If an item is in memory, re-use it.
		if (isset($this->items['' . $itemID])) {
			$this->items['' . $itemID]['qty'] += $quantity;
		} else {
			$this->addItem(new Item($itemID), $quantity);
		}
	}

	function addItem(Item $item, $quantity = 1) {
		if (!isset($this->items['' . $item->getItemID()])) {
			$this->items['' . $item->getItemID()] = [ 'item' => $item, 'qty' => $quantity];
		} else {
			$this->items['' . $item->getItemID()]['qty'] += $quantity;
		}
	}

	function removeItemByID($itemID, $quantity = 1) {
		$this->removeItem($this->items['' . $itemID]['item'], $quantity);
	}

	function removeItem(Item $item, $quantity = 1) {
		$qty = $this->items['' . $item->getItemID()]['qty'] -= $quantity;

		if ($qty < 1) {
			unset($this->items['' . $item->getItemID()]);
		}
	}

	public function getItems() {
		return $this->items;
	}

	public function getTotalQunatity() {
		$totalQuantity = 0;
		foreach ($this->items as $item) {
			$totalQuantity += $item['qty'];
		}
		return $totalQuantity;
	}

	public function checkInputFields($email = null, $cellphone = null, $telephone = null, $userFields = null, $supplierFields = null) {
		if ($email !== null) {
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if (!preg_match($regex, $email)) {
				Cart::get()->setError('You have entered an invalid email address');
				return false;
			}
		}
		
		if ($cellphone !== null) {
			$regex = '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/';
			if (!preg_match($regex, $cellphone)) {
				Cart::get()->setError('You have entered an invalid cellphone number');
				return false;
			}
		}
		
		if ($telephone !== null) {
			$regex = '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/';
			if (!preg_match($regex, $cellphone)) {
				Cart::get()->setError('You have entered an invalid telephone number');
				return false;
			}
		}
		
		if($userFields !== null) {
			foreach ($userFields as $field) {
				if($field === '') {
					Cart::get()->setError('Please make sure that all fields have data');
					return false;
				}
			}
		}
		
		if($supplierFields !== null) {
			foreach ($supplierFields as $field) {
				if($field === '') {
					Cart::get()->setError('Please make sure that all fields have data');
					return false;
				}
			}
		}
		
		return true;
	}
	
	public function checkItemFields($fields = null) {
		if($fields !== null) {
			foreach ($fields as $field) {
				if($field === '') {
					$this->setError('Please make sure that all fields have data');
					return false;
				}
			}
		}
	}

	/**
	 * Returns the subtotal of a specific item.
	 * @param type $itemID
	 * @return int
	 */
	public function getSubTotalbyID($itemID) {
		return $this->items[$itemID]['item']->getNormalPrice() * $this->items[$itemID]['qty'];
	}

	/**
	 * Returns the total of all items.
	 */
	public function getTotal() {
		$total = 0;
		foreach ($this->items as $item) {
			$total += $this->getSubTotalbyID($item['item']->getItemID());
		}
		return $total;
	}

	public function setError($error) {
		$this->error = $error;
	}

	public function hasError() {
		if ($this->error != null) {
			$display = $this->error;
			$this->error = null;
			return $display;
		}
		return false;
	}

	static function get() {
		if (!isset($_SESSION['cart'])) {
			$_SESSION['cart'] = new Cart();
		}
		return $_SESSION['cart'];
	}

	static function destroy() {
		unset($_SESSION['cart']);
	}

	public function getSearch() {
		return $this->search;
	}

	public function setSearch($search) {
		$this->search = $search;
	}

}
?>

