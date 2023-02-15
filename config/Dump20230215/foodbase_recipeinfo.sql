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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-15 18:43:33
