<?php

require_once (__DIR__.'/table-system/autoloader.php');

//!----------- OPTIONS -----------
define('TM_PSQL_NAMESPACE', 'PSQL');

$TABLE_SYSTEM_OPTIONS['PATH_TO_PSQL'] = __DIR__ . '/system-library/PSQL.class.php';

//styles
define('TM_BUTTON_CLASS', 'ESmallButton');
define('TM_CONTAINER_CLASS', 'ETablePanel');
define('TM_TABLE_CLASS', 'ETable');
define('TM_FIELD_CLASS', 'MField');

//JS
define('TM_JS_NAMESPACE', 'TM.');

//other
define('TM_SESSION_NAME', 'STORED_TABLE_MODELS');

spl_autoload_register(function ($class) {

	// Creates a string containing the path to the class.
	$search = __DIR__ . '/system-library/' . $class . '.class.php';

	// Check if file is in the directory.
	if (!is_file($search)) {
		die('Could not autoload' . $search);
		return false;
	}

	// Require the class.
	require_once($search);
	return true;
}, false);

session_start();
PSQL::readConfig();
?>