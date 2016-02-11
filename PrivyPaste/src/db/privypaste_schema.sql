-- MySQL dump 10.14  Distrib 5.5.46-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: privypaste_testing
-- ------------------------------------------------------
-- Server version	5.5.46-MariaDB-1~wheezy-log

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
-- Table structure for table `pastes`
--

DROP TABLE IF EXISTS `pastes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pastes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(8) NOT NULL,
  `created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `ciphertext` text,
  `initialization_vector` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pastes`
--

LOCK TABLES `pastes` WRITE;
/*!40000 ALTER TABLE `pastes` DISABLE KEYS */;
INSERT INTO `pastes` VALUES (1,'3a2d2812','2016-02-11 03:03:58','2016-02-11 03:03:58','e5f04763326b1ca26bdceb655224681c63f2b51b37349acce82aa8af67043db4.JTqQmCd9sA3O69na20GpBH2b5F2+QzeQuL+2hN1JGCJ0FFa/6RAFIuxWpFZymFeOgnYNJ8fT9Fw32glQ8smzGgIbMwXZ2iF31hyh4bqlm6SvAXmIBWrG+wvtctcHZkoXgCp5gqiVqqjNHUxFxF+YlcZFnnJwzF5bOTYrgA/++m5WzDaYTV5EiHodD5bMLX34bREYXqd/I2n96DL+Wa3jfAqoE9+TgXpVYr/nZw/Tf07BCOntogaW5TAMFynyCEUw4ZCTnTpcm14D+5dZe3iO/8xhjn40FSfpQ4Pgd+8SyjKjDC8TU5ldOZ4+gXWbQ6ny9E2U+MOdj+IJYtZSauJa7lwv86LeS7FCEMYKNdhdxSSnCTbaKGthuNz0vX7G6LMkogRnU6eRt3QM+tmOuz5+ZeO9jKTMg2Mo0HcbD4DsD1ery0teh2hvKTQs0CKhuiUS3xVRY8e7G/kwiHAAQgKBhiDPCNJqnF3badAKXqRISUorkIz+ena8U/o+aAEeQb0pAvnTTzZ1jDIhnpsxDS4YKDQdGmMgpOeTfise4J9yX4pS3dP6vYsay8vBoYqtpm37HvHLORc4tPQL970dMS6zoE1WWvlZ2HqCmSvFZh0A9T3WvTQr6OLLuMVVns9QlxDx0C9xJdmiwohnHflw43QMobxKY8svkmVkch3mid08MzpCciXOjACbJOmjY24vMq41ybaOSY03qSjGem4quzUdH5tmcnah5maDdAhzFPQ/4+wRXXNi+kagRK6qCaKeuhVAgxWg6tg0hHhf7gx4FaO/hglWA2sZWAwJ7sDpV8z1ghCnSTPUFSt4PJMrs37gyl+tTGM15P6xHgTQXcnqyXEQgI/FAdsFYTOMOMOtkgA9BaLbrwKJBbgupho7+irckQEWgsTJFvqRmvlEQZWYn9RYOVSKVN2WVdEJksmlT1l','3d872409e8a80d9d5ad69dbd46a039c2');
/*!40000 ALTER TABLE `pastes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-11  3:05:50
