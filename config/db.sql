-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: foodbase
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `categoryid` int NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL,
  `picture` blob,
  PRIMARY KEY (`categoryid`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'postres',NULL),(2,'jugo',NULL),(3,'sopas',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingholder`
--

DROP TABLE IF EXISTS `ingholder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingholder` (
  `ingid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`ingid`),
  KEY `fk_user_ingholder` (`username`),
  CONSTRAINT `fk_user_ingholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingholder`
--

LOCK TABLES `ingholder` WRITE;
/*!40000 ALTER TABLE `ingholder` DISABLE KEYS */;
/*!40000 ALTER TABLE `ingholder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ingredient` (`ingredient`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredients`
--

LOCK TABLES `ingredients` WRITE;
/*!40000 ALTER TABLE `ingredients` DISABLE KEYS */;
INSERT INTO `ingredients` VALUES (26,'azúcar'),(25,'harina'),(27,'huevos'),(28,'mantequilla'),(30,'sal'),(29,'vainilla');
/*!40000 ALTER TABLE `ingredients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `recipeid` int NOT NULL AUTO_INCREMENT,
  `recipename` varchar(50) NOT NULL,
  `categoryid` int NOT NULL,
  `preparation` text NOT NULL,
  `observation` text,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cookingtime` int DEFAULT NULL,
  PRIMARY KEY (`recipeid`),
  UNIQUE KEY `recipename` (`recipename`),
  KEY `fk_recipe_categories` (`categoryid`),
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (19,'Bizcocho de avena',1,'ffsffsfgsgfsgf','','2023-02-15 01:27:13',50),(20,'Flan de fresa',2,'hthrthr\r\nhjghjgh','','2023-02-15 21:17:17',20),(21,'Sopa de pollo',3,'jhhjhgjghjghjgjgh','','2023-02-15 21:18:39',20),(22,'Bizcocho de chocolate',1,'fgsgdfgdfgdfgdfgdfgfdgfdg\r\nghghgfhgfh','','2023-02-15 21:20:21',50);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipeinfo`
--

DROP TABLE IF EXISTS `recipeinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipeinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipename` varchar(50) NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipeinfo_recipe` (`recipename`),
  KEY `fk_recipeinfo_units` (`unit`),
  CONSTRAINT `fk_recipeinfo_recipe` FOREIGN KEY (`recipename`) REFERENCES `recipe` (`recipename`),
  CONSTRAINT `fk_recipeinfo_units` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipeinfo`
--

LOCK TABLES `recipeinfo` WRITE;
/*!40000 ALTER TABLE `recipeinfo` DISABLE KEYS */;
INSERT INTO `recipeinfo` VALUES (13,'Bizcocho de avena',6.00,'gramos','azúcar'),(14,'Bizcocho de avena',4.00,'unidades','huevos'),(15,'Bizcocho de avena',7.00,'gramos','harina'),(16,'Bizcocho de avena',7.00,'gramos','mantequilla'),(17,'Flan de fresa',6.00,'gramos','mantequilla'),(18,'Flan de fresa',10.00,'cucharaditas','sal'),(19,'Flan de fresa',5.00,'unidades','huevos'),(20,'Sopa de pollo',6.00,'unidades','huevos'),(21,'Sopa de pollo',4.00,'gramos','mantequilla'),(22,'Sopa de pollo',7.00,'gramos','harina'),(23,'Bizcocho de chocolate',7.00,'gramos','mantequilla'),(24,'Bizcocho de chocolate',4.00,'unidades','huevos'),(25,'Bizcocho de chocolate',7.00,'cucharaditas','vainilla'),(26,'Bizcocho de chocolate',6.00,'gramos','sal');
/*!40000 ALTER TABLE `recipeinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `recipeinfoview`
--

DROP TABLE IF EXISTS `recipeinfoview`;
/*!50001 DROP VIEW IF EXISTS `recipeinfoview`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `recipeinfoview` AS SELECT 
 1 AS `recipeid`,
 1 AS `recipename`,
 1 AS `date`,
 1 AS `cookingtime`,
 1 AS `preparation`,
 1 AS `observation`,
 1 AS `category`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `recipeview`
--

DROP TABLE IF EXISTS `recipeview`;
/*!50001 DROP VIEW IF EXISTS `recipeview`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `recipeview` AS SELECT 
 1 AS `recipename`,
 1 AS `indications`,
 1 AS `date`,
 1 AS `cookingtime`,
 1 AS `preparation`,
 1 AS `observation`,
 1 AS `category`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `reholder`
--

DROP TABLE IF EXISTS `reholder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reholder` (
  `re_id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`re_id`),
  KEY `fk_units_reholder` (`unit`),
  KEY `fk_ingredients_reholder` (`ingredient`),
  KEY `fk_user_reholder` (`username`),
  CONSTRAINT `fk_ingredients_reholder` FOREIGN KEY (`ingredient`) REFERENCES `ingredients` (`ingredient`),
  CONSTRAINT `fk_units_reholder` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`),
  CONSTRAINT `fk_user_reholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reholder`
--

LOCK TABLES `reholder` WRITE;
/*!40000 ALTER TABLE `reholder` DISABLE KEYS */;
/*!40000 ALTER TABLE `reholder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `unitid` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`unitid`),
  UNIQUE KEY `unit` (`unit`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (8,'cucharaditas'),(6,'gramos'),(7,'unidades');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `recipeinfoview`
--

/*!50001 DROP VIEW IF EXISTS `recipeinfoview`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `recipeinfoview` AS select `r`.`recipeid` AS `recipeid`,`r`.`recipename` AS `recipename`,`r`.`date` AS `date`,`r`.`cookingtime` AS `cookingtime`,`r`.`preparation` AS `preparation`,`r`.`observation` AS `observation`,`c`.`category` AS `category` from (`recipe` `r` join `categories` `c` on((`r`.`categoryid` = `c`.`categoryid`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `recipeview`
--

/*!50001 DROP VIEW IF EXISTS `recipeview`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `recipeview` AS select `ri`.`recipename` AS `recipename`,concat_ws(' ',`ri`.`quantity`,`ri`.`unit`,'de',`ri`.`ingredient`) AS `indications`,concat_ws('-',convert(date_format(`r`.`date`,'%d') using utf8mb4),convert(date_format(`r`.`date`,'%m') using utf8mb4),convert(date_format(`r`.`date`,'%Y') using utf8mb4)) AS `date`,`r`.`cookingtime` AS `cookingtime`,`r`.`preparation` AS `preparation`,`r`.`observation` AS `observation`,`c`.`category` AS `category` from ((`recipeinfo` `ri` join `recipe` `r` on((`ri`.`recipename` = `r`.`recipename`))) join `categories` `c` on((`r`.`categoryid` = `c`.`categoryid`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-15 19:18:42
