<?php

class SiteUser extends User {

	public function __construct($email, $password) {
		$query = PSQL::query("SELECT userID FROM tblUsers WHERE email = ? AND password = SHA1(?)", "ONLINE_SHOP", array($email, $password));

		if ($result = $query->fetch()) {
			parent::__construct($result['userID']);
			$_SESSION['user'] = $this;
		} else {
			Cart::get()->setError('Invalid username or password');
			header('Location:../index.php');
		}
	}

	/**
	 * 
	 * @return SiteUser
	 */
	static function get() {
		if (isset($_SESSION['user'])) {
			return $_SESSION['user'];
		}
		return false;
	}

	static function destroy() {
		unset($_SESSION['user']);
	}

}
