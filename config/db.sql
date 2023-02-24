-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: foodbase
-- ------------------------------------------------------
-- Server version	8.0.28

DROP DATABASE IF EXISTS foodbase;

CREATE DATABASE foodbase;
 
USE foodbase;

CREATE TABLE `categories` (
  `categoryid` int NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL,
  PRIMARY KEY (`categoryid`)
); 


INSERT INTO `categories` VALUES (1,'postres'),(2,'jugo'),(3,'sopas');


CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ingredient` (`ingredient`)
);

INSERT INTO `ingredients` VALUES (26,'azúcar'),(25,'harina'),(27,'huevos'),(28,'mantequilla'),(30,'sal');

CREATE TABLE `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
);

INSERT INTO `users` (username, fullname, password, status) VALUES ('Admin', 'Magdiel Castillo', '123456', 'Admin');

CREATE TABLE `ingholder` (
  `ingid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`ingid`),
  KEY `fk_ingredient_ingholder` (`ingredient`),
  KEY `fk_user_ingholder` (`username`),
  CONSTRAINT `fk_ingredient_ingholder` FOREIGN KEY (`ingredient`) REFERENCES `ingredients` (`ingredient`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_ingholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `ingholder` VALUES (9,NULL,'harina');


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
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`) ON DELETE CASCADE ON UPDATE CASCADE
); 


INSERT INTO `recipe` VALUES (21,'Sopa de pato',2,'Esta receta es buena','Es buena','2023-02-15 21:18:39',30),(22,'Bizcocho de chocolate',1,'fgsgdfgdfgdfgdfgdfgfdgfdgghghgfhgfh','','2023-02-15 21:20:21',50), (23,'Bizcocho de vainilla',1,'fgsgdfgh','','2023-02-15 21:20:21',50), (24,'Flan de vainilla',2,'fdfhsdf dfdfdh','','2023-02-15 21:20:21',20);

CREATE TABLE `units` (
  `unitid` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`unitid`),
  UNIQUE KEY `unit` (`unit`)
);

INSERT INTO `units` VALUES (8,'cucharaditas'),(6,'gramos'),(7,'unidades');

CREATE TABLE `recipeinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipename` varchar(50) NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `ingredient` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipeinfo_recipe` (`recipename`),
  KEY `fk_ingredients_recipeinfo` (`ingredient`),
  KEY `fk_recipeinfo_units` (`unit`),
  CONSTRAINT `fk_ingredients_recipeinfo` FOREIGN KEY (`ingredient`) REFERENCES `ingredients` (`ingredient`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipeinfo_recipe` FOREIGN KEY (`recipename`) REFERENCES `recipe` (`recipename`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipeinfo_units` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE
);


INSERT INTO `recipeinfo` VALUES (20,'Sopa de pato',6.00,'unidades','huevos'),(21,'Sopa de pato',4.00,'gramos','mantequilla'),(22,'Sopa de pato',7.00,'gramos','harina'),(23,'Bizcocho de chocolate',7.00,'gramos','mantequilla'),(24,'Bizcocho de chocolate',4.00,'unidades','huevos'),(26,'Bizcocho de chocolate',6.00,'gramos','sal');


DROP VIEW IF EXISTS `recipeinfoview`;

CREATE VIEW `recipeinfoview` AS select `r`.`recipeid` AS `recipeid`,`r`.`recipename` AS `recipename`,`r`.`date` AS `date`,`r`.`cookingtime` AS `cookingtime`,`r`.`preparation` AS `preparation`,`r`.`observation` AS `observation`,`c`.`category` AS `category` from (`recipe` `r` join `categories` `c` on((`r`.`categoryid` = `c`.`categoryid`)));


CREATE VIEW `recipeview` AS select `ri`.`recipename` AS `recipename`,concat_ws(' ',`ri`.`quantity`,`ri`.`unit`,'de',`ri`.`ingredient`) AS `indications`,concat_ws('-',convert(date_format(`r`.`date`,'%d') using utf8mb4),convert(date_format(`r`.`date`,'%m') using utf8mb4),convert(date_format(`r`.`date`,'%Y') using utf8mb4)) AS `date`,`r`.`cookingtime` AS `cookingtime`,`r`.`preparation` AS `preparation`,`r`.`observation` AS `observation`,`c`.`category` AS `category` from ((`recipeinfo` `ri` join `recipe` `r` on((`ri`.`recipename` = `r`.`recipename`))) join `categories` `c` on((`r`.`categoryid` = `c`.`categoryid`)));

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
  CONSTRAINT `fk_ingredients_reholder` FOREIGN KEY (`ingredient`) REFERENCES `ingredients` (`ingredient`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_units_reholder` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_reholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);


INSERT INTO `reholder` VALUES (56,'azúcar',6.00,'cucharaditas',NULL);




