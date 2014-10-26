<?php

/**
  This class defines a much faster and better approach to working with databases
  in PHP. It will provide error checking and escapes on all inserts and will
  also manage the internal workings.

  @author Dylan Vorster

 */
class PSQL {

	protected static $DBHost = NULL;
	protected static $DBUser = NULL;
	protected static $DBPass = NULL;
	protected static $DBName = "LP_USERS";
	public static $sqlres;
	public static $result;
	protected static $configRead = false;
	public static $queries = NULL;

	public static function readConfig() {
		if (!self::$configRead) {
			if (!is_file(__DIR__ . "/config.php")) {
				throw new SQLMissingConfigException();
			}
			require_once(__DIR__ . "/config.php");
			self::$DBHost = MYSQL_HOST;
			self::$DBUser = MYSQL_USER;
			self::$DBPass = MYSQL_PASS;

			self::$configRead = true;
		}
		return true;
	}

	/**
	  Internal dont use it (most cases)
	 */
	public static function connect($db) {
		if (is_null(self::$sqlres)) {
			self::$sqlres = array();
		}

		self::readConfig();

		if (!isset(self::$sqlres[$db])) {
			self::$sqlres[$db] = new \PDO('mysql:host=' . self::$DBHost . ';dbname=' . $db, self::$DBUser, self::$DBPass);
			self::$sqlres[$db]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		return self::$sqlres[$db];
	}

	/**
	  Internal dont use it (most cases)
	 */
	public static function prepare($sql, $db) {
		self::connect($db);

		//if the queries are null, add them
		if (is_null(self::$queries)) {
			self::$queries = array();
		}

		if (!isset(self::$queries['' . $db])) {
			self::$queries[$db] = array();
		}

		//if the queries are null, add them
		foreach (self::$queries[$db] AS $query) {
			if ($query['query'] == $sql) {
				return $query['statement'];
			}
		}

		$stmt = self::$sqlres[$db]->prepare($sql);
		self::$queries[$db][] = array('query' => $sql, 'statement' => $stmt);
		return $stmt;
	}

	/**
	 * Use this function to insert data into a table
	 * @param sql - the SQL Query with '?' for placeholders
	 * @param db - the database
	 * @param vars - the variables in an array format
	 */
	public static function insert($sql, $db, $vars = array()) {
		self::connect($db);

		if (!is_resource(self::$sqlres[$db])) {
			self::connect($db);
		}
		$stmt = self::$sqlres[$db]->prepare($sql);
		if (!is_array($vars)) {
			throw new \Exception("VARS must be an array");
		}
		$length = count($vars);
		for ($i = 1; $i <= $length; $i++) {
			$stmt->bindParam($i, $vars[$i - 1]);
		}
		$stmt->execute();
		return self::$sqlres[$db]->lastInsertId();
	}

	/**
	 * Use this function to execute a prepared statement
	 * @param sql - the SQL Query with '?' for placeholders
	 * @param db - the database
	 * @param vars - the variables in an array format
	 */
	public static function insertSet($sql, $db, $set = array()) {
		$stmt = self::prepare($sql, $db);
		if (!is_array($set)) {
			throw new \Exception("VARS must be an array");
		}
		foreach ($set as $vars) {
			$length = count($vars);
			for ($i = 1; $i <= $length; $i++) {
				$stmt->bindParam($i, $vars[$i - 1]);
			}
			$stmt->execute();
		}
		return true;
	}

	/**
	 * Use this function to execute a prepared statement
	 * @param sql - the SQL Query with '?' for placeholders
	 * @param db - the database
	 * @param vars - the variables in an array format
	 */
	public static function query($sql, $db, $vars = array()) {
		$stmt = self::prepare($sql, $db);
		if (!is_array($vars)) {
			
		}
		$length = count($vars);
		for ($i = 1; $i <= $length; $i++) {
			$stmt->bindParam($i, $vars[$i - 1]);
		}
		$stmt->execute();
		return $stmt;
	}
}

?>
