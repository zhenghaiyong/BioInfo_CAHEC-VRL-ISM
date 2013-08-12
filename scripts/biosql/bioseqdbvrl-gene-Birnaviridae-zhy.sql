-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 08 月 11 日 22:10
-- 服务器版本: 5.1.70
-- PHP 版本: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `bioseqdbvrl`
--

-- --------------------------------------------------------

--
-- 表的结构 `gene`
--

CREATE TABLE IF NOT EXISTS `gene` (
  `gene_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `remark` text,
  `parent_id` int(10) unsigned NOT NULL,
  `biodatabase_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`gene_id`),
  KEY `gene_name` (`name`),
  KEY `gene_parent` (`parent_id`),
  KEY `gene_db` (`biodatabase_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `gene`
--

INSERT INTO `gene` (`gene_id`, `name`, `remark`, `parent_id`, `biodatabase_id`) VALUES
(1, 'Genome', NULL, 0, 1),
(2, 'Segment A', NULL, 1, 1),
(3, 'Segment B', NULL, 1, 1),
(4, 'Ployprotein', NULL, 2, 1),
(5, 'Others', NULL, 2, 1),
(6, 'VP1', NULL, 3, 1),
(7, 'Others', NULL, 3, 1),
(8, 'VP2', NULL, 4, 1),
(9, 'VP3', NULL, 4, 1),
(10, 'VP4', NULL, 4, 1),
(11, 'Others', NULL, 4, 1);

--
-- 限制导出的表
--

--
-- 限制表 `gene`
--
/*
-- Can't create table 'bioseqdbvrl.#sql-9b_40' (errno: 121)
ALTER TABLE `gene`
  ADD CONSTRAINT `FKbiodatabase_gene` FOREIGN KEY (`biodatabase_id`) REFERENCES `biodatabase` (`biodatabase_id`);
*/
