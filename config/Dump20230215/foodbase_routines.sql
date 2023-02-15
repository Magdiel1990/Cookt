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

-- Dump completed on 2023-02-15 18:43:34
