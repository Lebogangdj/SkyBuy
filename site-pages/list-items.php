<?php
$title = 'Browse Items';
require_once '../header.php';

$itemObject = new Item();
$items = [];

if (!isset($_POST['search'])) {
	$_POST['search'] = '';
}

if (isset($_POST['clearSearch'])) {
	$_POST['search'] = '';
	Cart::get()->setSearch(null);
}

//dont have search terms yet so lets get one from the cart
if ($_POST['search'] === '') {

	//we have something in the cart to retrieve
	if (Cart::get()->getSearch() !== null) {
		$_POST['search'] = Cart::get()->getSearch();
	}
}


if ($_POST['search'] !== '') {
	$items = $itemObject->getItemIDOnTags(processTags($_POST['search']));
	Cart::get()->setSearch($_POST['search']);
} else {
	$items = $itemObject->getAllItemsID();
}

function processTags() {
	$tags = explode(',', $_POST['search']);
	for ($count = 0; $count < sizeof($tags); $count++) {
		$tags[$count] = preg_replace('/\s+/', '', $tags[$count]);
	}
	return $tags;
}
?>

<div class='encapsulate_content'>
	<h2><?php echo $title ?></h2>
	<form method="post" action="http://<?php echo $_SERVER['SERVER_ADDR']; ?>/site-pages/list-items.php">
		<table>
			<td>
				<input style="width: 200px;"type="text" placeholder="graphics,food,electronics" name="search" value="<?php echo isset($_POST['search']) ? $_POST['search'] : "" ?>">
			</td>
			<td>
				<input class="submit" type="submit" name="submitSearch" value="Search">
			</td>
			<td>
				<input class="submit" style="background-color: rgba(0,192,255,1) !important;" type="submit" name="clearSearch" value="Show All">
			</td>
		</table>
		<?php
		if (sizeof($items) > 0) {
			echo '<div class="listEncapsulation">';
			foreach ($items as $item) {
				?>
				<div class="wrap">
					<div class="item">
						<div class="image" style="background-image: url(<?php echo '../item-images/' . $item->getItemImage() ?>);"></div>
						<div class="data">
							<h3><?php echo $item->getItemName(); ?></h3>
							<h4><?php echo $item->getItemDescription(true); ?></h4>
							<p class="pricebox">R <?php echo $item->getNormalPrice() ?></p>
							<span style="float: right; margin-top: 10px;">
								<a class="button" href="view-item.php?itemID=<?php echo $item->getItemID(); ?>">View</a>
								<a class="button" style="background-color: rgba(192,255,0,0.8);" href="../site-engines/cart_engine.php?cartItemID=<?php echo $item->getItemID(); ?>&page=list-items">Add To Cart</a>
							</span>
						</div>
					</div>
				</div>
				<?php
			}
			echo '</div>';
		}
		?>
	</form>
</div>

<?php
require_once '../footer.php';
?>