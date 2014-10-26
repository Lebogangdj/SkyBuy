<?php

require_once '../autoloader.php';

/**
 * Login the user.
 */
if (isset($_POST['credentials'])) {
	$credentials = $_POST['credentials'];
	$split = explode(",", $credentials);
	$siteUser = new SiteUser($split[0], $split[1]);

	echo '<?xml version="1.0"?>';
	echo '<user>';
	echo '<firstName>' . SiteUser::get()->getFirstName() . '</firstName>';
	echo '<lastName>' . SiteUser::get()->getLastName() . '</lastName>';
	echo '<userID>' . SiteUser::get()->getUserID() . '</userID>';
	echo '</user>';
}

/**
 * Logout the user.
 */
if (isset($_POST['logout'])) {
	SiteUser::destroy();

	echo '<?xml version="1.0"?>';
	echo '<user>';
	echo '<firstName>logged</firstName>';
	echo '<lastName>out</lastName>';
	echo '<userID>0</userID>';
	echo '</user>';
}
?>