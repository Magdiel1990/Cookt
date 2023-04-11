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

INSERT INTO `categories` VALUES (1,'jugos'),(2,'batidos'),(3,'postres'),(4,'salsas'),(5,'sopas'),(6,'pastas'),(7,'ensaladas'),(8,'tés'),(9,'almuerzos'),(10,'desayunos y cenas'),(11,'bebidas calientes'),(12,'snacks'),(13,'guarniciones'),(14,'platos principales');

CREATE TABLE `type` (
typeid int NOT NULL AUTO_INCREMENT,
`type` varchar(15) NOT NULL unique,
-- Determine what goes here. --
description text,
primary key (typeid)
);

INSERT INTO `type` VALUES (1,'Admin',NULL),(2,'Standard',NULL),(3,'Viewer',NULL);

CREATE TABLE `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL UNIQUE,
  `firstname` varchar(30) not null,
  `lastname` varchar(40) not null,
  `password` varchar(255) NOT NULL,
  `type` varchar(15) NOT NULL,
  `email`  varchar(70),
  `state` boolean not null,
  `sex` char(1) not null,
  `created_at` timestamp default current_timestamp,
  `updated_at` timestamp default current_timestamp,
  PRIMARY KEY (`userid`),
  CHECK (`sex` in ("M","F","O")),
  CONSTRAINT `fk_users_type`  FOREIGN KEY (`type`) references `type` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `users` (userid, username, firstname, lastname, password, type, email, state, sex) VALUES (1,'Admin','Magdiel', 'Castillo','$2y$10$YYmOuD0hBlD.Lb3f6EpxKekpcNG6ogf5CaVfEy4LmIPwqSImW/abC','Admin','magdielmagdiel1@gmail.com',1,'M'),(2,'Patricia','Patricia', 'Paola','$2y$10$CntUz0zV/ndiovMs1Pkf..lltlCUq3yMrT3jIJXAFrbFtk.7ur4W.','Admin','yibeli100@gmail.com',1,'F'),(3,'Missael','Missael', 'Castillo','$2y$10$Z.xyp82ioWU.fXRMJxUtKuudHPeNKyYhoROTyJ4qc3PDHj9Q4MTFO','Viewer','',1,'M'),(4,'Lisandro','Lisandro', 'Polanco','$2y$10$EhvxQ/0kstgRoT326dbkPOyfw2E34c0NG8IkkBqX745HcRLi6zKFu','Standard','',1,'M');

CREATE TABLE access (
id int not null auto_increment,
userid int not null,
lastlogin timestamp DEFAULT CURRENT_TIMESTAMP,
primary key (id),
CONSTRAINT `fk_access_users` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
);

create table recovery (
id int not null auto_increment,
userid int not null,
forgot_pass_identity varchar(32) not null COLLATE utf8mb4_unicode_ci,
primary key(id),
constraint fk_recovery_users FOREIGN KEY (`userid`) references `users` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `ingredients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ingredient` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `created_at` timestamp default current_timestamp,
  `updated_at` timestamp default current_timestamp,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `ingredients` (id, ingredient, username) VALUES (1,'habichuela','Admin'),(2,'cebolla','Admin'),(3,'ajo','Admin'),(4,'apio','Admin'),(6,'ají','Admin'),(7,'puerro','Admin'),(8,'arroz','Admin'),(9,'agua','Admin'),(11,'sal','Admin'),(12,'aceite','Admin'),(13,'carne','Admin'),(14,'salsa de tomate','Admin'),(15,'pimienta','Admin');

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
  `created_at` timestamp default current_timestamp,
  `updated_at` timestamp default current_timestamp,
  PRIMARY KEY (`recipeid`),
  CHECK (`cookingtime` >= 0),
  CONSTRAINT `fk_recipe_categories` FOREIGN KEY (`categoryid`) REFERENCES `categories` (`categoryid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipe_users` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
); 

INSERT INTO `recipe` (recipeid, recipename, categoryid, preparation, date, cookingtime, username) VALUES (1,'Habichuelas dominicanas',13,'Pon a hervir las habichuelas con agua y sal 25 minutos. Sofríelas con la cebolla, el ajo, ají, el apio, los puerros y la sal. Mezcla las habichuelas con el sofrito y deja hervir por 30 minutos hasta que la mezcla espese.','2023-03-19 23:19:52',55,'Admin'),(2,'Arroz blanco',14,'En una olla vierte el arroz, la sal, el aceite y el agua y cocina hasta que esté listo.','2023-03-19 23:47:25',30,'Admin'),(3,'Carne para arroz blanco',13,'Sofríe la carne con la cebolla, el ajo, el ají y el apio. Vierte la pasta de tomate y deja hervir. Añade sal y pimienta. Sirve las habichuelas, el arroz y la carne y disfruta la bandera dominicana en todo su esplendor.','2023-03-19 23:50:43',40,'Admin');

CREATE TABLE `units` (
  `unitid` int NOT NULL AUTO_INCREMENT,
  `unit` varchar(20) NOT NULL,
  PRIMARY KEY (`unitid`),
  UNIQUE KEY `unit` (`unit`)
);

INSERT INTO `units` VALUES (12,'al gusto'),(8,'cucharadas'),(1,'cucharaditas'),(16,'diente'),(2,'gramos'),(11,'kilogramos'),(15,'lata'),(7,'libras'),(6,'litros'),(4,'mililitros'),(10,'onzas'),(9,'pizca'),(14,'rodaja'),(13,'tallo'),(5,'tazas'),(3,'unidades');

CREATE TABLE `recipeinfo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipeid` INT NOT NULL,
  `quantity` varchar(8) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `ingredientid` INT NOT NULL,
  `detail` varchar(100),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_ingredients_recipeinfo` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_recipeinfo_recipe` FOREIGN KEY (`recipeid`) REFERENCES `recipe` (`recipeid`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `recipeinfo` VALUES (1,1,'1/2','tazas',1,''),(2,1,'1/2','unidades',2,'finamente picada'),(3,1,'1','diente',3,'pequeño machucado'),(4,1,'1/2','tallo',4,'picado'),(5,1,'1/2','unidades',6,'picado'),(6,1,'1','al gusto',11,''),(7,1,'1','al gusto',15,''),(8,2,'1','tazas',8,''),(9,2,'2','tazas',9,''),(10,2,'1','cucharadas',11,''),(11,2,'2','cucharadas',12,''),(12,3,'1/2','libras',13,''),(13,3,'1','unidades',2,'finamente picada'),(14,3,'1','diente',3,'picado'),(15,3,'1','unidades',6,'picado'),(16,3,'1','lata',14,'pequeña'),(17,3,'1','al gusto',11,''),(18,3,'1','al gusto',15,'');

CREATE TABLE `reholder` (
  `re_id` int NOT NULL AUTO_INCREMENT,
  `ingredientid` INT NOT NULL,
  `quantity` varchar(8) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `detail` varchar(100),
  PRIMARY KEY (`re_id`),
  CONSTRAINT `fk_ingredients_reholder` FOREIGN KEY (`ingredientid`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_units_reholder` FOREIGN KEY (`unit`) REFERENCES `units` (`unit`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_reholder` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE recipeimgs (
imgid int NOT NULL AUTO_INCREMENT,
imgname varchar(50),
imgext varchar(5),
userid int NOT NULL,
recipeid int NOT NULL,
`size` int not null,
PRIMARY KEY (imgid),
CHECK (imgext in ("jpg", "png", "jpeg","giff")),
CONSTRAINT `fk_recipeimgs_recipes` FOREIGN KEY (`recipeid`) REFERENCES `recipe` (`recipeid`) on delete cascade on update cascade,
CONSTRAINT `fk_recipeimgs_users` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`) on delete cascade on update cascade
);

