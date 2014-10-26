CREATE DATABASE  IF NOT EXISTS `ONLINE_SHOP` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ONLINE_SHOP`;
-- MySQL dump 10.13  Distrib 5.5.32, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ONLINE_SHOP
-- ------------------------------------------------------
-- Server version	5.5.32-0ubuntu0.13.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tblRatings`
--

DROP TABLE IF EXISTS `tblRatings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblRatings` (
  `ratingID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rating` int(1) unsigned NOT NULL DEFAULT '3',
  `itemID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ratingID`),
  KEY `rating` (`rating`),
  KEY `itemID` (`itemID`),
  KEY `userID` (`userID`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblRatings`
--

LOCK TABLES `tblRatings` WRITE;
/*!40000 ALTER TABLE `tblRatings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblRatings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTransactionItems`
--

DROP TABLE IF EXISTS `tblTransactionItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTransactionItems` (
  `transactionID` int(10) unsigned NOT NULL,
  `itemID` int(10) unsigned NOT NULL,
  `priceID` int(10) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`transactionID`,`itemID`,`priceID`),
  KEY `quantity` (`quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the items and amounts that were part of a transaction';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTransactionItems`
--

LOCK TABLES `tblTransactionItems` WRITE;
/*!40000 ALTER TABLE `tblTransactionItems` DISABLE KEYS */;
INSERT INTO `tblTransactionItems` VALUES (1,1,12,1),(3,1,13,1),(4,2,14,1),(2,1,13,2),(4,1,13,2),(5,1,13,6);
/*!40000 ALTER TABLE `tblTransactionItems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblItemSupplier`
--

DROP TABLE IF EXISTS `tblItemSupplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblItemSupplier` (
  `itemID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  PRIMARY KEY (`itemID`,`supplierID`),
  KEY `itemID` (`itemID`),
  KEY `supplierID` (`supplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the link between a supplier and an item.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblItemSupplier`
--

LOCK TABLES `tblItemSupplier` WRITE;
/*!40000 ALTER TABLE `tblItemSupplier` DISABLE KEYS */;
INSERT INTO `tblItemSupplier` VALUES (1,8),(2,10),(3,9),(10,8),(11,8),(12,8),(14,8),(15,9);
/*!40000 ALTER TABLE `tblItemSupplier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblPrice`
--

DROP TABLE IF EXISTS `tblPrice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblPrice` (
  `priceID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `costPrice` varchar(45) DEFAULT NULL,
  `normalPrice` varchar(45) DEFAULT NULL,
  `itemID` int(11) NOT NULL,
  `active` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`priceID`),
  KEY `costPrice` (`costPrice`),
  KEY `normalPrice` (`normalPrice`),
  KEY `itemID` (`itemID`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1 COMMENT='Stores the price of items in the database.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblPrice`
--

LOCK TABLES `tblPrice` WRITE;
/*!40000 ALTER TABLE `tblPrice` DISABLE KEYS */;
INSERT INTO `tblPrice` VALUES (5,'8','10',9,'INACTIVE'),(6,'10','20',10,'INACTIVE'),(7,'8.50','10.50',11,'INACTIVE'),(8,'2','2.50',12,'INACTIVE'),(9,'10','20',13,'INACTIVE'),(10,'10.50','20.50',14,'INACTIVE'),(11,'20','30',15,'INACTIVE'),(12,'2.50','4.50',1,'INACTIVE'),(13,'2.50','10',1,'ACTIVE'),(14,'6.50','8',2,'ACTIVE'),(15,'5.50','9',3,'ACTIVE');
/*!40000 ALTER TABLE `tblPrice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblRights`
--

DROP TABLE IF EXISTS `tblRights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblRights` (
  `userID` int(10) unsigned NOT NULL,
  `status` enum('ADMIN') NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Specifies special rights to a user, such as being an admin.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblRights`
--

LOCK TABLES `tblRights` WRITE;
/*!40000 ALTER TABLE `tblRights` DISABLE KEYS */;
INSERT INTO `tblRights` VALUES (2,'ADMIN'),(3,'ADMIN');
/*!40000 ALTER TABLE `tblRights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTransactions`
--

DROP TABLE IF EXISTS `tblTransactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTransactions` (
  `transactionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `collected` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('COLLECTION','DELIVERY','VOID') NOT NULL,
  PRIMARY KEY (`transactionID`),
  KEY `userID` (`userID`),
  KEY `reference` (`reference`),
  KEY `total` (`total`),
  KEY `timestamp` (`timestamp`),
  KEY `collected` (`collected`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COMMENT='Stores information about the transaction that a user made.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTransactions`
--

LOCK TABLES `tblTransactions` WRITE;
/*!40000 ALTER TABLE `tblTransactions` DISABLE KEYS */;
INSERT INTO `tblTransactions` VALUES (1,2,'5263118c93d57',4,'2014-10-19 23:11:08','0000-00-00 00:00:00','COLLECTION'),(2,2,'526316177fedc',20,'2014-10-19 23:30:31','0000-00-00 00:00:00','COLLECTION'),(3,2,'52634e091f61b',10,'2014-10-20 03:29:13','0000-00-00 00:00:00','COLLECTION'),(4,3,'5263d35316622',28,'2014-10-20 12:57:55','0000-00-00 00:00:00','COLLECTION'),(5,2,'52689d2d8f982',60,'2014-10-24 04:08:13','0000-00-00 00:00:00','COLLECTION'),(6,2,'5269a950523b3',0,'2014-10-24 23:12:16','0000-00-00 00:00:00','DELIVERY'),(7,2,'5269a9f424415',0,'2014-10-24 23:15:00','0000-00-00 00:00:00','DELIVERY'),(8,2,'5269aa45b3348',0,'2014-10-24 23:16:21','0000-00-00 00:00:00','DELIVERY'),(9,2,'5269aacb032a8',0,'2014-10-24 23:18:35','0000-00-00 00:00:00','DELIVERY'),(10,2,'5269ab21d1b62',0,'2014-10-24 23:20:01','0000-00-00 00:00:00','DELIVERY'),(11,2,'5269ab51a014c',0,'2014-10-24 23:20:49','0000-00-00 00:00:00','DELIVERY'),(12,2,'5269ab822922c',0,'2014-10-24 23:21:38','0000-00-00 00:00:00','DELIVERY'),(13,2,'5269abcd1d011',0,'2014-10-24 23:22:53','0000-00-00 00:00:00','DELIVERY'),(14,2,'5269ac34854ce',0,'2014-10-24 23:24:36','0000-00-00 00:00:00','DELIVERY'),(15,0,'5269ac7261fbb',0,'2014-10-24 23:25:38','0000-00-00 00:00:00',''),(16,2,'5269ade0d169c',0,'2014-10-24 23:31:44','0000-00-00 00:00:00','DELIVERY'),(17,2,'5269aea9cc236',0,'2014-10-24 23:35:05','0000-00-00 00:00:00','DELIVERY');
/*!40000 ALTER TABLE `tblTransactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblUsers`
--

DROP TABLE IF EXISTS `tblUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblUsers` (
  `userID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL DEFAULT '',
  `lastName` varchar(50) NOT NULL DEFAULT '',
  `title` enum('MR','MS','NA') DEFAULT 'NA',
  `email` varchar(100) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `address` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `firstName` (`firstName`),
  KEY `lastName` (`lastName`),
  KEY `title` (`title`),
  KEY `email` (`email`),
  KEY `timestamp` (`timestamp`),
  KEY `address` (`address`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Stores user information such as their name, surname, email, ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblUsers`
--

LOCK TABLES `tblUsers` WRITE;
/*!40000 ALTER TABLE `tblUsers` DISABLE KEYS */;
INSERT INTO `tblUsers` VALUES (2,'Colin','Landman','MR','landmancolin@gmail.com','2014-10-10 22:03:43','33 Searle Street George','b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'),(3,'John','Appleseed','MR','john@mail.com','2014-10-20 12:57:04','31 Somewhere Lane, Some City, Code','b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3'),(4,'Test','User','MR','testuser@mail.com','2014-10-22 17:52:01','1 Somewhere Lane, Somewhere City, Code','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');
/*!40000 ALTER TABLE `tblUsers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblImages`
--

DROP TABLE IF EXISTS `tblImages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblImages` (
  `imageID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`imageID`),
  KEY `itemID` (`itemID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Stores information about an image, such as size. name and co';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblImages`
--

LOCK TABLES `tblImages` WRITE;
/*!40000 ALTER TABLE `tblImages` DISABLE KEYS */;
INSERT INTO `tblImages` VALUES (1,1),(2,2),(3,3);
/*!40000 ALTER TABLE `tblImages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblItems`
--

DROP TABLE IF EXISTS `tblItems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblItems` (
  `itemID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemName` varchar(45) NOT NULL,
  `itemDescription` varchar(255) DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  `active` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `hardStock` int(11) unsigned NOT NULL,
  `softStock` int(10) unsigned NOT NULL,
  PRIMARY KEY (`itemID`),
  KEY `itemName` (`itemName`),
  KEY `itemDescription` (`itemDescription`),
  KEY `timestamp` (`timestamp`),
  KEY `userID` (`userID`),
  KEY `active` (`active`),
  KEY `hardStock` (`hardStock`),
  KEY `softStock` (`softStock`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COMMENT='Stores item details such as name, description, date added, e';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblItems`
--

LOCK TABLES `tblItems` WRITE;
/*!40000 ALTER TABLE `tblItems` DISABLE KEYS */;
INSERT INTO `tblItems` VALUES (1,'Apples','Fruit that\'s fresh and yum! Jay!','2014-10-19 23:10:42',2,'ACTIVE',10,10),(2,'Oranges','Filled to the brim with Vitamin C.','2014-10-20 12:28:59',2,'ACTIVE',10,10),(3,'Tomatoes','Just add sugar when cooking.','2014-10-20 12:51:41',2,'ACTIVE',10,10);
/*!40000 ALTER TABLE `tblItems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblLogin`
--

DROP TABLE IF EXISTS `tblLogin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblLogin` (
  `loginID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`loginID`),
  KEY `userID` (`userID`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores information about logins from the user.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblLogin`
--

LOCK TABLES `tblLogin` WRITE;
/*!40000 ALTER TABLE `tblLogin` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblLogin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblItemTags`
--

DROP TABLE IF EXISTS `tblItemTags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblItemTags` (
  `itemID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL,
  PRIMARY KEY (`itemID`,`tagID`),
  KEY `itemID` (`itemID`),
  KEY `tagID` (`tagID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores tags that are attached to items.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblItemTags`
--

LOCK TABLES `tblItemTags` WRITE;
/*!40000 ALTER TABLE `tblItemTags` DISABLE KEYS */;
INSERT INTO `tblItemTags` VALUES (1,3),(1,16),(2,3),(2,18),(3,3),(3,19);
/*!40000 ALTER TABLE `tblItemTags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblSuppliers`
--

DROP TABLE IF EXISTS `tblSuppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblSuppliers` (
  `supplierID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplierName` varchar(75) NOT NULL,
  `telephone` varchar(12) DEFAULT '',
  `cellphone` varchar(12) DEFAULT '',
  `address` varchar(75) DEFAULT '',
  `email` varchar(100) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  PRIMARY KEY (`supplierID`),
  KEY `supplierName` (`supplierName`),
  KEY `telephone` (`telephone`),
  KEY `cellphone` (`cellphone`),
  KEY `address` (`address`),
  KEY `email` (`email`),
  KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COMMENT='Stores suppliers that provide specific items.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblSuppliers`
--

LOCK TABLES `tblSuppliers` WRITE;
/*!40000 ALTER TABLE `tblSuppliers` DISABLE KEYS */;
INSERT INTO `tblSuppliers` VALUES (8,'Example Supplier','044 846 2154','073 548 5135','1 Somewhere Lane, Some City, Some Code','exampleone@somewhere.com','This is an example supplier.'),(9,'Another Example Supplier','021 548 6987','083 486 7954','2 Somewhere Lane, Some City, Some Code','suppliertwo@mail.com','This is an example of another supplier.');
/*!40000 ALTER TABLE `tblSuppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblTags`
--

DROP TABLE IF EXISTS `tblTags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblTags` (
  `tagID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`tagID`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1 COMMENT='Makes different categories available for items.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblTags`
--

LOCK TABLES `tblTags` WRITE;
/*!40000 ALTER TABLE `tblTags` DISABLE KEYS */;
INSERT INTO `tblTags` VALUES (2,'awesome'),(10,'durr'),(3,'food'),(16,'fruit'),(1,'graphics'),(9,'hurr'),(8,'lol'),(7,'tag'),(14,'tasty'),(5,'test'),(19,'vegetable'),(18,'vitamins');
/*!40000 ALTER TABLE `tblTags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblStockAdjustments`
--

DROP TABLE IF EXISTS `tblStockAdjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tblStockAdjustments` (
  `adjustmentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `supplierID` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`adjustmentID`),
  KEY `itemID` (`itemID`),
  KEY `supplierID` (`supplierID`),
  KEY `quantity` (`quantity`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores the changes that happens to items.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblStockAdjustments`
--

LOCK TABLES `tblStockAdjustments` WRITE;
/*!40000 ALTER TABLE `tblStockAdjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tblStockAdjustments` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-25 10:10:08
