<?php

class User {

	// Variables that make up the user.
	private $userID;
	private $firstName;
	private $lastName;
	private $title;
	private $email;
	private $address;
	private $password;
	// Containers for things that pertain to the user.
	private $rights;

	function __construct($userID = NULL) {
		if ($userID !== NULL) {
			$this->userID = $userID;
			$this->fetchCurrentUser();
		}
	}

	/**
	 * Loads the user based on their userID.
	 * @return \User|boolean 
	 */
	function fetchCurrentUser() {
		$query = PSQL::query(''
						. 'SELECT tblUsers.*, tblRights.status '
						. 'FROM tblUsers '
						. ''
						. 'LEFT OUTER JOIN tblRights '
						. 'ON tblUsers.userID = tblRights.userID '
						. ''
						. 'WHERE tblUsers.userID = ?', 'ONLINE_SHOP', array($this->userID));

		if (($result = $query->fetch()) != false) {
			$this->userID = $result['userID'];
			$this->firstName = $result['firstName'];
			$this->lastName = $result['lastName'];
			$this->title = $result['title'];
			$this->email = $result['email'];
			$this->address = $result['address'];
			$this->rights = $result['status'];
			$this->password = $result['password'];
			return $this;
		}
		return false;
	}

	/**
	 *  Inserts the user object into the database.
	 */
	function insertCurrentUser() {
		$userID = PSQL::insert('INSERT INTO tblUsers 
			(firstName, 
			lastName, 
			title, 
			email, 
			address, 
			password) 
			
			VALUES (?, ?, ?, ?, ?, SHA1(?))', 'ONLINE_SHOP', array($this->firstName, $this->lastName, $this->title, $this->email, $this->address, $this->password));
		
		return $userID;
	}

	/**
	 * Updates the current user.
	 * @return type
	 */
	function updateCurrentuser($rights = 'no') {
		PSQL::query('UPDATE tblUsers 
			SET firstName = ?, 
			lastName = ?, 
			email = ?, 
			address = ? 
			WHERE userID = ?', 'ONLINE_SHOP', array($this->firstName, $this->lastName, $this->email, $this->address, $this->userID));
		
		if($rights === 'yes') {
			PSQL::query("INSERT INTO tblRights 
				(userID,
				status)
				
				VALUES (?, 'ADMIN')", 'ONLINE_SHOP', array($this->userID));
		} else {
		PSQL::query("DELETE FROM tblRights
			WHERE userID = ?", 'ONLINE_SHOP', array($this->userID));
		}
	}

	/**
	 * Removes the user by ID
	 * @return type
	 */
	function deleteUserByID($userID) {
		PSQL::query('DELETE FROM tblUsers WHERE userID = ?', 'ONLINE_SHOP', array($userID));
	}

	/**
	 * Gets the past transactions that the user has made.
	 * @return type
	 */
	function getPastPurchases() {
		$transactions = [];

		$query = PSQL::query(
						'SELECT *
			FROM tblTransactions 
			WHERE userID = ?', 'ONLINE_SHOP', array($this->userID));

		while ($result = $query->fetch()) {
			$transactions[$result['transactionID']] = ['info' => $result, 'items' => []];

			$innerQuery = PSQL::query("SELECT 
				tblItems.itemName, 
				tblItems.itemDescription, 
				tblTransactionItems.quantity, 
				tblPrice.normalPrice
				
				FROM tblItems

				INNER JOIN tblTransactionItems
				ON tblTransactionItems.itemID = tblItems.itemID
				
				LEFT OUTER JOIN tblPrice
				ON tblTransactionItems.priceID = tblPrice.priceID
				
				WHERE tblTransactionItems.transactionID = ?", 'ONLINE_SHOP', array($result['transactionID']));

			while ($innerResult = $innerQuery->fetch()) {
				$transactions[$result['transactionID']]['items'][] = $innerResult;
			}
		}

		return $transactions;
	}

	function isAdmin() {
		return $this->rights == 'ADMIN';
	}

	/* ========== Setter Functions ========== */

	public function setUserID($userID) {
		$this->userID = $userID;
	}

	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function setRights($rights) {
		$this->rights = $rights;
	}

	/* ========== Getter Functions ========= */

	public function getUserID() {
		return $this->userID;
	}

	public function getFirstName() {
		return $this->firstName;
	}

	public function getLastName() {
		return $this->lastName;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function getAddress() {
		return $this->address;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getRights() {
		return $this->rights;
	}

}

?>