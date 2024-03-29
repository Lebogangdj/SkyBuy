<?php

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-10-22 at 23:41:04.
 */
class ItemTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Item
	 */
	protected $item;
	protected $itemID;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->item = new Item;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		
	}

	public function testInsertItem() {
		$this->item = new Item();
		$this->item->setItemName('Test Item');
		$this->item->setItemDescription('Test description.');
		$this->item->setUserID(2);
		$this->item->setHardStock(10);
		$this->item->setSoftStock(10);
		$this->itemID = $this->item->insertItem();
	}

	/**
	 * @depends testInsertItem
	 */
	public function testFetchItemByID() {
		$this->item = new Item($this->itemID);

		$this->assertEquals('Test Item', $this->item->getItemName());
		$this->assertEquals('Test description.', $this->item->getItemDescription());
		$this->assertEquals(2, $this->item->getUserID());
		$this->assertEquals(10, $this->item->getHardStock());
		$this->assertEquals(10, $this->item->getSoftStock());
	}

	/**
	 * @depends testFetchItemByID
	 */
	public function testUpdateItemById() {
		$this->item->setItemName('Updated Name');
		$this->item->setItemDescription('Updated description.');
		$this->item->setUserID(3);
		$this->item->setHardStock(11);
		$this->item->setSoftStock(11);

		$this->item = new Item($this->itemID);

		$this->assertEquals('Updated Item', $this->item->getItemName());
		$this->assertEquals('Updated description.', $this->item->getItemDescription());
		$this->assertEquals(3, $this->item->getUserID());
		$this->assertEquals(11, $this->item->getHardStock());
		$this->assertEquals(11, $this->item->getSoftStock());
	}

	/**
	 * @depends testUpdateItemById
	 */
	public function testMarkInactiveByID() {
		$this->item->markInactiveByID();
	}

	/**
	 * @depends testMarkInactiveByID
	 */
	public function testAddImage() {
		$this->item->addImage();
	}

	/**
	 * @depends testAddImage
	 */
	public function testRemoveImage() {
		$this->item->removeImage();
	}

	/**
	 * @depends testRemoveImage
	 */
	public function testAddPrice() {
		$this->item->addPrice(15, 16);
	}

	/**
	 * @depends testAddPrice
	 */
	public function testAddTags() {
		$this->item->addTags(array('word'));
		// Contents tested in testGetAllTags
	}

	/**
	 * @depends testGetAllTags
	 */
	public function testGetAllTags() {
		$this->assertEquals('word', $this->item->getAllTags());
	}

	public function testRemoveTag() {
		$this->item->removeTag('word');

		$this->assertEquals(null, $this->item->getAllTags());
	}

	public function testRemoveAllTags() {
		$this->item->removeAllTags();
	}

}
