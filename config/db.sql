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

INSERT INTO `categories` 
VALUES (1,'postres'),(2,'jugo'),(3,'sopas');

CREATE TABLE `type` (
typeid int NOT NULL AUTO_INCREMENT,
`type` varchar(15) NOT NULL unique,
description text,
primary key (typeid)
);

INSERT INTO `type` (type) 
VALUES ('Admin'), ('Standard'), ('Viewer');

CREATE TABLE `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL UNIQUE,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL,
  `email`  varchar(70),
  `state` boolean not null,
  `reportsto` varchar(30),
  PRIMARY KEY (`userid`),
  CONSTRAINT `fk_users_type`  FOREIGN KEY (`type`) references `type` (`type`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_users`  FOREIGN KEY (`reportsto`) references `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE  
);

INSERT INTO `users` (`username`, `fullname`, `password`, `type`, `email`, `state`) 
VALUES ('Admin', 'Magdiel Castillo', '123456', 'Admin', 'magdielmagdiel1@gmail.com', 1);

CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`)
);

INSERT INTO `ingredients` 
VALUES (1,'azúcar', 'Admin'),
(2,'harina','Admin'),
(3,'huevos','Admin'),
(4,'mantequilla','Admin'),
(5,'sal','Admin');

CREATE TABLE `ingholder` (
  `ingid` INT NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `ingredientid` INT NOT NULL,
  PRIMARY KEY (`ingid`),
  CONSTRAINT `fk_ingredient_ingholder` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_ingholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `ingholder` VALUES (9, 'Admin', 2);

CREATE TABLE `recipe` (
  `recipeid` int NOT NULL AUTO_INCREMENT,
  `recipename` varchar(50) NOT NULL,
  `categoryid` int NOT NULL,
  `preparation` text NOT NULL,
  `observation` text,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `cookingtime` int,
  `username` varchar(30) not null,
  PRIMARY KEY (`recipeid`),
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipe_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
); 

INSERT INTO `recipe` 
VALUES (1,'Sopa de pato',2,'Esta receta es buena','Es buena','2023-02-15 21:18:39',30,'Admin'),
(2,'Bizcocho de chocolate',1,'fgsgdfgdfgdfgdfgdfgfdgfdgghghgfhgfh','','2023-02-15 21:20:21',50,'Admin'), 
(3,'Bizcocho de vainilla',1,'fgsgdfgh','','2023-02-15 21:20:21',50,'Admin'), 
(4,'Flan de vainilla',2,'fdfhsdf dfdfdh','','2023-02-15 21:20:21',30,'Admin');

CREATE TABLE `units` (
  `unitid` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`unitid`),
  UNIQUE KEY `unit` (`unit`)
);

INSERT INTO `units` VALUES (1,'cucharaditas'),(2,'gramos'),(3,'unidades');

CREATE TABLE `recipeinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipeid` INT NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `ingredientid` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_recipeinfo` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipeinfo_recipe` FOREIGN KEY (`recipeid`) REFERENCES `recipe` (`recipeid`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `recipeinfo` 
VALUES (1,1,6.00,'unidades',3),
(2,1,4.00,'gramos',4),
(3,1,7.00,'gramos',2),
(4,2,7.00,'gramos',4),
(5,2,4.00,'unidades',3),
(6,2,6.00,'gramos',5);

CREATE VIEW `recipeinfoview` 
AS select `r`.`recipeid` 
AS `recipeid`,`r`.`recipename` 
AS `recipename`,`r`.`date` 
AS `date`,`r`.`cookingtime` 
AS `cookingtime`,`r`.`preparation` 
AS `preparation`,`r`.`observation` 
AS `observation`,`c`.`category` 
AS `category`, `r`.`username`
AS `username`
from `recipe` `r` join `categories` `c` 
on `r`.`categoryid` = `c`.`categoryid`;

CREATE VIEW `recipeview` 
AS select `r`.`recipename` 
AS `recipename`,concat_ws(' ',`ri`.`quantity`,`ri`.`unit`,'de',`i`.`ingredient`) 
AS `indications`,concat_ws('-',convert(date_format(`r`.`date`,'%d') using utf8mb4),convert(date_format(`r`.`date`,'%m') using utf8mb4),convert(date_format(`r`.`date`,'%Y') using utf8mb4)) 
AS `date`,`r`.`cookingtime` AS `cookingtime`,`r`.`preparation` 
AS `preparation`,`r`.`observation` AS `observation`,`c`.`category`, `r`.`username`
AS `username` 
from `recipeinfo` `ri` 
join `recipe` `r`
on `ri`.`recipeid` = `r`.`recipeid` 
join `ingredients` `i`
on  `i`.`id` = `ri`.`ingredientid`
join `categories` `c` 
on `r`.`categoryid` = `c`.`categoryid`;

CREATE TABLE `reholder` (
  `re_id` int NOT NULL AUTO_INCREMENT,
  `ingredientid` INT NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY (`re_id`),
  CONSTRAINT `fk_ingredients_reholder` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_units_reholder` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_reholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `reholder` VALUES (1,1,6.00,'cucharaditas','Admin');
