-- MySQL dump 10.13  Distrib 5.1.71, for apple-darwin11.4.2 (i386)
--
-- Host: localhost    Database: cahecvrl
-- ------------------------------------------------------
-- Server version	5.1.71

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
-- Table structure for table `vrl_access`
--

DROP TABLE IF EXISTS `vrl_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_access`
--

LOCK TABLES `vrl_access` WRITE;
/*!40000 ALTER TABLE `vrl_access` DISABLE KEYS */;
INSERT INTO `vrl_access` (`role_id`, `node_id`, `level`, `pid`, `module`) VALUES (2,1,1,0,NULL),(2,40,2,1,NULL),(3,1,1,0,NULL),(2,30,2,1,NULL),(2,50,3,40,NULL),(3,50,3,40,NULL),(1,50,3,40,NULL),(3,7,2,1,NULL),(3,39,3,30,NULL),(2,39,3,30,NULL),(2,49,3,30,NULL),(4,1,1,0,NULL),(4,2,2,1,NULL),(4,3,2,1,NULL),(4,4,2,1,NULL),(4,5,2,1,NULL),(4,6,2,1,NULL),(4,7,2,1,NULL),(4,11,2,1,NULL),(5,25,1,0,NULL),(5,51,2,25,NULL),(1,1,1,0,NULL),(1,39,3,30,NULL),(1,40,2,1,NULL),(1,49,3,30,NULL),(3,69,2,1,NULL),(3,30,2,1,NULL),(3,40,2,1,NULL),(1,37,3,30,NULL),(1,36,3,30,NULL),(1,35,3,30,NULL),(1,34,3,30,NULL),(1,33,3,30,NULL),(1,32,3,30,NULL),(1,31,3,30,NULL),(2,32,3,30,NULL),(2,31,3,30,NULL),(7,1,1,0,NULL),(1,30,2,1,NULL),(7,40,2,1,NULL),(7,30,2,1,NULL),(7,50,3,40,NULL),(7,39,3,30,NULL),(7,49,3,30,NULL);
/*!40000 ALTER TABLE `vrl_access` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_form`
--

DROP TABLE IF EXISTS `vrl_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_form` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_form`
--

LOCK TABLES `vrl_form` WRITE;
/*!40000 ALTER TABLE `vrl_form` DISABLE KEYS */;
/*!40000 ALTER TABLE `vrl_form` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_group`
--

DROP TABLE IF EXISTS `vrl_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_group`
--

LOCK TABLES `vrl_group` WRITE;
/*!40000 ALTER TABLE `vrl_group` DISABLE KEYS */;
INSERT INTO `vrl_group` (`id`, `name`, `title`, `create_time`, `update_time`, `status`, `sort`, `show`) VALUES (2,'System','System',1222841259,0,1,0,0),(5,'Search','Search',0,0,1,0,0),(7,'Manage','Manage',0,0,1,0,0);
/*!40000 ALTER TABLE `vrl_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_node`
--

DROP TABLE IF EXISTS `vrl_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_node`
--

LOCK TABLES `vrl_node` WRITE;
/*!40000 ALTER TABLE `vrl_node` DISABLE KEYS */;
INSERT INTO `vrl_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `type`, `group_id`) VALUES (49,'read','查看',1,'',NULL,30,3,0,0),(40,'Index','默认模块',1,'',1,1,2,0,0),(39,'index','列表',1,'',NULL,30,3,0,0),(37,'resume','恢复',1,'',NULL,30,3,0,0),(36,'forbid','禁用',1,'',NULL,30,3,0,0),(35,'foreverdelete','删除',1,'',NULL,30,3,0,0),(34,'update','更新',1,'',NULL,30,3,0,0),(33,'edit','编辑',1,'',NULL,30,3,0,0),(32,'insert','写入',1,'',NULL,30,3,0,0),(31,'add','新增',1,'',NULL,30,3,0,0),(30,'Public','公共模块',1,'',2,1,2,0,0),(69,'Form','数据管理',1,'',1,1,2,0,2),(7,'User','用户管理',1,'',4,1,2,0,2),(6,'Role','角色管理',1,'',3,1,2,0,2),(2,'Node','节点管理',1,'',2,1,2,0,2),(1,'VRL','后台管理',1,'',NULL,0,1,0,0),(50,'main','空白首页',1,'',NULL,40,3,0,0);
/*!40000 ALTER TABLE `vrl_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_role`
--

DROP TABLE IF EXISTS `vrl_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_role`
--

LOCK TABLES `vrl_role` WRITE;
/*!40000 ALTER TABLE `vrl_role` DISABLE KEYS */;
INSERT INTO `vrl_role` (`id`, `name`, `pid`, `status`, `remark`, `ename`, `create_time`, `update_time`) VALUES (1,'Manage',0,1,'','',1208784792,1376983518),(2,'Search',0,1,'','',1215496283,1376983533),(7,'Demo',0,1,'',NULL,1254325787,1376983541);
/*!40000 ALTER TABLE `vrl_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_role_user`
--

DROP TABLE IF EXISTS `vrl_role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_role_user`
--

LOCK TABLES `vrl_role_user` WRITE;
/*!40000 ALTER TABLE `vrl_role_user` DISABLE KEYS */;
INSERT INTO `vrl_role_user` (`role_id`, `user_id`) VALUES (4,'27'),(4,'26'),(4,'30'),(5,'31'),(3,'22'),(3,'1'),(1,'4'),(2,'3'),(7,'2');
/*!40000 ALTER TABLE `vrl_role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vrl_user`
--

DROP TABLE IF EXISTS `vrl_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vrl_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vrl_user`
--

LOCK TABLES `vrl_user` WRITE;
/*!40000 ALTER TABLE `vrl_user` DISABLE KEYS */;
INSERT INTO `vrl_user` (`id`, `account`, `nickname`, `password`, `bind_account`, `last_login_time`, `last_login_ip`, `login_count`, `verify`, `email`, `remark`, `create_time`, `update_time`, `status`, `type_id`, `info`) VALUES (1,'admin','ADMIN','21232f297a57a5a743894a0e4a801fc3','',1377070274,'0.0.0.0',894,'8888','liu21st@gmail.com','备注信息',1222907803,1326266696,1,0,''),(2,'demo','DEMO','fe01ce2a7fbac8fafaed7c982a04e229','',1376985345,'0.0.0.0',91,'8888','','',1239783735,1376983882,1,0,''),(3,'search','SEARCH','06a943c59f33a34bb5924aaf72cd2995','',1376985332,'0.0.0.0',18,'','','',1253514375,1376983907,1,0,''),(4,'manage','MANAGE','70682896e24287b0476eff2a14c148f0','',1376985311,'0.0.0.0',17,'','','',1253514575,1376983928,1,0,'');
/*!40000 ALTER TABLE `vrl_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-08-21 15:36:15
