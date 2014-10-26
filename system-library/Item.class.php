<?php

class Item {

	// Table identifiers.
	private $itemID;
	private $userID;
	private $priceID;
	// Variables that makes an item.
	private $itemName;
	private $itemDescription;
	private $hardStock;
	private $softStock;
	// Objects that pertain to an item.
	private $costPrice;
	private $normalPrice;
	private $itemImage;

	function __construct($itemID = NULL) {
		if ($itemID != NULL) {
			$this->itemID = $itemID;
			$this->fetchItemByID();
		}
	}

	/**
	 * Fetch the item by its ID and fill all fields.
	 * @return \Item
	 */
	function fetchItemByID() {
		$query = PSQL::query("SELECT 
			tblItems.*,
			tblImages.imageID,
			tblPrice.costPrice,
			tblPrice.normalPrice,
			tblPrice.priceID
			FROM tblItems
			
			INNER JOIN tblImages
			ON tblItems.itemID = tblImages.itemID
			
			INNER JOIN tblPrice
			ON tblItems.itemID = tblPrice.itemID
			AND tblPrice.active = 'ACTIVE'

			WHERE tblItems.itemID = ?", "ONLINE_SHOP", array($this->itemID));

		if ($result = $query->fetch()) {
			$this->itemID = $result['itemID'];
			$this->itemName = $result['itemName'];
			$this->itemDescription = $result['itemDescription'];
			$this->userID = $result['userID'];
			$this->hardStock = $result['hardStock'];
			$this->softStock = $result['softStock'];
			$this->costPrice = $result['costPrice'];
			$this->normalPrice = $result['normalPrice'];
			$this->itemImage = $result['imageID'];
			$this->priceID = $result['priceID'];

			return $this;
		}
		return false;
	}

	/**
	 * Returns the ID of all items that have the specified tags attached to them
	 * @param type $tags
	 * @return \Item
	 */
	function getItemIDOnTags($tags) {
		$items = [];
		$tagID = [];

		foreach ($tags as $tag) {
			$query = PSQL::query(
							'SELECT 
					tblTags.tagID
					FROM tblTags

					INNER JOIN tblItemTags
					ON tblTags.tagID = tblItemTags.tagID

					WHERE tblTags.name = ?', 'ONLINE_SHOP', array($tag));

			if ($result = $query->fetch()) {
				$tagID[] = $result['tagID'];
			}
		}

		try {
			$itemQuery = PSQL::query(
							'SELECT DISTINCT
					tblItems.itemID
					FROM tblItems 

					INNER JOIN tblItemTags
					ON tblItems.itemID = tblItemTags.itemID

					WHERE tblItemTags.tagID IN (' . implode(',', $tagID) . ')
					AND tblItems.active = "ACTIVE"', 'ONLINE_SHOP');

			while ($itemResult = $itemQuery->fetch()) {
				$items[] = new Item($itemResult['itemID']);
			}
		} catch (Exception $e) {
			
		}
		return $items;
	}

	/**
	 * Returns all item IDs found in the database
	 * @return \Item
	 */
	function getAllItemsID() {
		$items = [];

		try {
			$itemQuery = PSQL::query(
							'SELECT 
				tblItems.itemID
				FROM tblItems 
				
				WHERE tblItems.active = "ACTIVE"', 'ONLINE_SHOP');

			while ($itemResult = $itemQuery->fetch()) {
				$items[] = new Item($itemResult['itemID']);
			}
		} catch (Exception $e) {
			
		}
		return $items;
	}

	/**
	 * Update the Item
	 */
	function updateItemById() {
		PSQL::query('UPDATE tblItems SET itemName = ?, itemDescription = ?, hardStock = ?, softStock = ? WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemName, $this->itemDescription, $this->hardStock, $this->softStock, $this->itemID));
		PSQL::query("UPDATE tblPrice SET active = 'INACTIVE' WHERE itemID = ?", 'ONLINE_SHOP', array($this->itemID));
		PSQL::query('INSERT INTO tblPrice (costPrice, normalPrice, itemID) VALUES (?, ?, ?)', 'ONLINE_SHOP', array($this->costPrice, $this->normalPrice, $this->itemID));
	}

	/**
	 *  Inserts the current item into the database.
	 */
	function insertItem() {
		$this->itemID = PSQL::insert(
						"INSERT INTO tblItems (itemName, itemDescription, userID, hardStock, softStock) 
				VALUES (?, ?, ?, ?, ?)", "ONLINE_SHOP", array($this->itemName, $this->itemDescription, $this->userID, $this->hardStock, $this->softStock));

		return $this->itemID;
	}

	/**
	 * Set the specific item as inactive in the database.
	 * @param type $itemID
	 */
	function markInactiveByID() {
		PSQL::query('UPDATE tblItems SET active = \'INACTIVE\' WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID));
	}

	/**
	 * Adds an image to the item.
	 * @param Image $image 
	 */
	function addImage() {
		$imageID = PSQL::insert('INSERT INTO tblImages (itemID) VALUES (?)', 'ONLINE_SHOP', array($this->itemID));
		return $imageID;
	}

	/**
	 * Delets the image from hard disk as well as the database.
	 * @param type $image
	 */
	function removeImage() {
		$query = PSQL::query('SELECT imageID FROM tblImages WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID));

		if (($result = $query->fetch()) !== false) {
			PSQL::query('DELETE FROM tblImages WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID, $result['imageID']));
		}

		if (file_exists(__DIR__ . '/../item-images/' . $result['imageID'])) {
			unlink(__DIR__ . '/../item-images/' . $result['imageID']);
		}
	}

	/**
	 * Adds the cost price and normal price to the item.
	 * @param type $costPrice
	 * @param type $normalPrice
	 */
	function addPrice($costPrice, $normalPrice) {
		$priceID = PSQL::insert('INSERT INTO tblPrice (costPrice, normalPrice, itemID, active) VALUES (?, ?, ?, \'ACTIVE\')', 'ONLINE_SHOP', array($costPrice, $normalPrice, $this->itemID));
	}

	/**
	 * Adds tags to the item that the user can search for.
	 * @param type $tags
	 */
	function addTags($tags = []) {
		PSQL::query('DELETE FROM tblItemTags WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID));
		foreach ($tags as $tag) {
			try {
				$tagID = PSQL::insert('INSERT INTO tblTags (name) VALUES (?)', 'ONLINE_SHOP', array($tag));
				PSQL::insert('INSERT INTO tblItemTags (itemID, tagID) VALUES (?, ?)', 'ONLINE_SHOP', array($this->itemID, $tagID));
			} catch (Exception $insertException) {
				$query = PSQL::query('SELECT tagID FROM tblTags WHERE name = ?', 'ONLINE_SHOP', array($tag))->fetch();
				PSQL::insert('INSERT INTO tblItemTags (itemID, tagID) VALUES (?, ?)', 'ONLINE_SHOP', array($this->itemID, $query['tagID']));
			}
		}
	}

	/**
	 * Get all the tags associated with an item.
	 */
	function getAllTags() {
		$query = PSQL::query('SELECT 
			GROUP_CONCAT(name SEPARATOR \', \') AS \'tags\'
			FROM tblTags

			INNER JOIN tblItemTags
			ON tblTags.tagID = tblItemTags.tagID

			WHERE tblItemTags.itemID = ?', 'ONLINE_SHOP', array($this->itemID));

		if (($result = $query->fetch()) !== false) {
			return $result['tags'];
		}
	}

	/**
	 * Removes the specified tag from the item.
	 * @param type $tag
	 */
	function removeTag($tag) {
		$query = PSQL::query('SELECT tagID FROM tblTags WHERE name = ?', 'ONLINE_SHOP', array($tag))->fetch();
		PSQL::query('DELETE FROM tblItemTags WHERE itemID = ? AND tagID = ?', 'ONLINE_SHOP', array($this->itemID, $query['tagID']));
	}

	/**
	 * Removes all tags from the item.
	 */
	function removeAllTags() {
		PSQL::query('DELETE FROM tblItemTags WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID));
	}

	/**
	 * This will add supplier(s) to the item.
	 */
	function addSupplier($suppliers) {
		if (is_array($suppliers)) {
			foreach ($suppliers as $supplier) {
				PSQL::insert('INSERT INTO tblItemSupplier (itemID, supplierID) VALUES (?, ?)', 'ONLINE_SHOP', array($this->itemID, $supplier));
			}
		} else {
			PSQL::insert('INSERT INTO tblItemSupplier (itemID, supplierID) VALUES (?, ?)', 'ONLINE_SHOP', array($this->itemID, $suppliers));
		}
	}

	/**
	 * Removes the specified supplier from the item.
	 * @param type $supplier
	 */
	function removeSupplier($supplier) {
		PSQL::query('DELETE FROM tblItemSupplier WHERE itemID = ? AND supplierID = ?', 'ONLINE_SHOP', array($this->itemID, $supplier));
	}

	/**
	 * Removes all supplier from the item.
	 */
	function removeAllSuppliers() {
		PSQL::query('DELETE FROM tblItemSupplier WHERE itemID = ?', 'ONLINE_SHOP', array($this->itemID));
	}

	/* ========== Setter Functions ========== */

	public function setItemID($itemID) {
		$this->itemID = $itemID;
	}

	public function setItemName($itemName) {
		$this->itemName = $itemName;
	}

	public function setItemDescription($itemDescription) {
		$this->itemDescription = $itemDescription;
	}

	public function setUserID($userID) {
		$this->userID = $userID;
	}

	public function setHardStock($hardStock) {
		$this->hardStock = $hardStock;
	}

	public function setSoftStock($softStock) {
		$this->softStock = $softStock;
	}

	public function setCostPrice($costPrice) {
		$this->costPrice = $costPrice;
	}

	public function setNormalPrice($normalPrice) {
		$this->normalPrice = $normalPrice;
	}

	public function setItemImage($itemImage) {
		$this->itemImage = $itemImage;
	}

	public function setPriceID($priceID) {
		$this->priceID = $priceID;
	}

	/* ========== Getter Functions ========== */

	public function getItemID() {
		return $this->itemID;
	}

	public function getItemName() {
		return $this->itemName;
	}

	public function getItemDescription($min = false) {
		if ($min) {
			if (strlen($this->itemDescription) > 40) {
				$stringCut = substr($this->itemDescription, 0, 50);
				$string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
				return $string;
			}
		}
		return $this->itemDescription;
	}

	public function getUserID() {
		return $this->userID;
	}

	public function getHardStock() {
		return $this->hardStock;
	}

	public function getSoftStock() {
		return $this->softStock;
	}

	public function getCostPrice() {
		return $this->costPrice;
	}

	public function getNormalPrice() {
		return $this->normalPrice;
	}

	public function getItemImage() {
		return $this->itemImage;
	}

	public function getPriceID() {
		return $this->priceID;
	}

}

?>