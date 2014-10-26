<?php

class Supplier {

	// The variables that make a Supplier.
	private $supplierName;
	private $telephone;
	private $cellphone;
	private $address;
	private $email;
	private $description;

	function __construct() {
		
	}

	/**
	 * Inserts the Supplier object into the database.
	 */
	function insertCurrentSupplier() {
		$supplierID = PSQL::insert(
				'INSERT INTO tblSuppliers 
				(supplierName, telephone, cellphone, address, email, description) 
				
				VALUES (?, ?, ?, ?, ?, ?)', 'ONLINE_SHOP', 
				array($this->supplierName, $this->telephone, $this->cellphone, $this->address, $this->email, $this->description));
		
		return $supplierID;
	}
	
	/**
	 * Fetch the Supplier based on ID.
	 */
	function fillSupplierByID($supplierID) {
		$query = PSQL::query(''
				. 'SELECT * '
				. 'FROM tblSuppliers '
				. 'WHERE supplierID = ?',
				'ONLINE_SHOP',
				array($supplierID));

		if (($result = $query->fetch()) != false) {
			$this->supplierID = $result['supplierID'];
			$this->supplierName = $result['supplierName'];
			$this->telephone = $result['telephone'];
			$this->cellphone = $result['cellphone'];
			$this->email = $result['email'];
			$this->description = $result['description'];
			$this->address = $result['address'];
			return $this;
		}
		return false;
	}
	
	/**
	 * Deletes the Supplier by ID.
	 */
	function deleteSupplierByID($supplierID) {
		PSQL::query('DELETE FROM tblSuppliers 
			WHERE supplierID = ?', 
			'ONLINE_SHOP', array($supplierID))		;
	}
	
	function updateSupplierByID($supplierID) {
		PSQL::query("UPDATE tblSuppliers 
			SET supplierName = ?, telephone = ?, cellphone = ?, address = ?, email = ?, description = ? 
			WHERE supplierID = ?", "ONLINE_SHOP", 
			array($this->supplierName, $this->telephone, $this->cellphone, $this->address, $this->email, $this->description, $supplierID));
	}

	/* ========== Setter Functions ========== */

	public function setSupplierName($supplierName) {
		$this->supplierName = $supplierName;
	}

	public function setTelephone($telephone) {
		$this->telephone = $telephone;
	}

	public function setCellphone($cellphone) {
		$this->cellphone = $cellphone;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	/* ========== Getter Functions ========== */

	public function getSupplierName() {
		return $this->supplierName;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getCellphone() {
		return $this->cellphone;
	}

	public function getAddress() {
		return $this->address;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getDescription() {
		return $this->description;
	}

}

?>