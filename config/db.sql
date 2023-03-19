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

INSERT INTO `categories` VALUES (1,'jugos'),(2,'batidos'),(3,'postres'),(4,'salsas'),(5,'sopas'),(6,'pastas'),(7,'ensaladas'),(8,'tés'),(9,'almuerzos'),(10,'desayunos y cenas'),(11,'bebidas calientes'),(12,'snacks');

CREATE TABLE `type` (
typeid int NOT NULL AUTO_INCREMENT,
`type` varchar(15) NOT NULL unique,
-- Determine what goes here. --
description text,
primary key (typeid)
);

INSERT INTO `type` (type) 
VALUES ('Admin'), ('Standard'), ('Viewer');

CREATE TABLE `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL UNIQUE,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(15) NOT NULL,
  `email`  varchar(70),
  `state` boolean not null,
  `reportsto` int,
  PRIMARY KEY (`userid`),
  CONSTRAINT `fk_users_type`  FOREIGN KEY (`type`) references `type` (`type`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_users_users`  FOREIGN KEY (`reportsto`) references `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE  
);

INSERT INTO `users` (`username`, `fullname`, `password`, `type`, `email`, `state`) 
VALUES ('Admin', 'Magdiel Castillo', '123456', 'Admin', 'magdielmagdiel1@gmail.com', 1), 
('Patricia', 'Patricia Paola', '123456', 'Admin', 'yibeli100@gmail.com', 1),
('Missael', 'Missael Castillo', '123456', 'Viewer', '', 1),
('Lisandro', 'Lisandro Polanco', '123456', 'Standard', '', 1);

CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`)
);

CREATE TABLE `ingholder` (
  `ingid` INT NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `ingredientid` INT NOT NULL,
  PRIMARY KEY (`ingid`),
  CONSTRAINT `fk_ingredient_ingholder` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_ingholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE `recipe` (
  `recipeid` int NOT NULL AUTO_INCREMENT,
  `recipename` varchar(50) NOT NULL,
  `categoryid` int NOT NULL,
  `preparation` text NOT NULL,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `cookingtime` int,
  `username` varchar(30) not null,
  PRIMARY KEY (`recipeid`),
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipe_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
); 


CREATE TABLE `units` (
  `unitid` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`unitid`),
  UNIQUE KEY `unit` (`unit`)
);

INSERT INTO `units` VALUES (8,'cucharadas'),(1,'cucharaditas'),(2,'gramos'),(11,'kilogramos'),(7,'libras'),(6,'litros'),(4,'mililitros'),(10,'onzas'),(9,'pizca'),(5,'tazas'),(3,'unidades'),(12,'al gusto'),(13,'tallo'), (14,'rodaja'), (15,'lata'),  (15,'diente');

CREATE TABLE `recipeinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipeid` INT NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `ingredientid` INT NOT NULL,
  `detail` varchar(100),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_recipeinfo` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipeinfo_recipe` FOREIGN KEY (`recipeid`) REFERENCES `recipe` (`recipeid`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE `reholder` (
  `re_id` int NOT NULL AUTO_INCREMENT,
  `ingredientid` INT NOT NULL,
  `quantity` double(5,2) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `detail` varchar(100),
  PRIMARY KEY (`re_id`),
  CONSTRAINT `fk_ingredients_reholder` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_units_reholder` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_reholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);