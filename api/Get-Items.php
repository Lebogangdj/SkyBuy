<?php require_once '../autoloader.php';

// Get the tags from Cloud Top
$tags = $_POST['tags'];

// Split the tags into seperate words
$splitTags = explode(",", $tags);

// Create new item objects based on tags and insert them into array
$tmp = new Item();
$items = $tmp->getItemIDOnTags($splitTags);

// Construct the XML that will be the response to Cloud Top
echo '<?xml version="1.0"?>';
echo '<items>';
foreach ($items as $item) {
	echo '<item id="'.$item->getItemID().'">';
		echo '<name>' . $item->getItemName() . '</name>';
		echo '<description>' . $item->getItemDescription() . '</description>';
		echo '<price>' . $item->getNormalPrice() . '</price>';
	echo '</item>';
}
echo '</items>';
?>
