-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 12 月 17 日 14:34
-- 服务器版本: 5.6.12
-- PHP 版本: 5.4.4

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
  `parent_id` int(10) unsigned DEFAULT NULL,
  `level` int(2) unsigned DEFAULT NULL,
  `left_value` int(10) unsigned DEFAULT NULL,
  `right_value` int(10) unsigned DEFAULT NULL,
  `biodatabase_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`gene_id`),
  KEY `gene_name` (`name`),
  KEY `gene_parent` (`parent_id`),
  KEY `gene_db` (`biodatabase_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `gene`
--

INSERT INTO `gene` (`gene_id`, `name`, `remark`, `parent_id`, `level`, `left_value`, `right_value`, `biodatabase_id`) VALUES
(1, 'Genome', NULL, 0, 0, 1, 22, 1),
(2, 'Segment A', NULL, 1, 1, 2, 15, 1),
(3, 'Segment B', NULL, 1, 1, 16, 21, 1),
(4, 'Ployprotein', NULL, 2, 2, 3, 12, 1),
(5, 'Others', NULL, 2, 2, 13, 14, 1),
(6, 'VP1', NULL, 3, 2, 17, 18, 1),
(7, 'Others', NULL, 3, 2, 19, 20, 1),
(8, 'VP2', NULL, 4, 3, 4, 5, 1),
(9, 'VP3', NULL, 4, 3, 6, 7, 1),
(10, 'VP4', NULL, 4, 3, 8, 9, 1),
(11, 'Others', NULL, 4, 3, 10, 11, 1),
(12, 'Segment 1', NULL, 0, 0, 1, 2, 2),
(13, 'Segment 2', NULL, 0, 0, 3, 4, 2),
(14, 'Segment 3', NULL, 0, 0, 5, 6, 2),
(15, 'Segment 4', NULL, 0, 0, 7, 8, 2),
(16, 'Segment 5', NULL, 0, 0, 9, 10, 2),
(17, 'Segment 6', NULL, 0, 0, 11, 12, 2),
(18, 'Segment 7', NULL, 0, 0, 13, 14, 2),
(19, 'Segment 8', NULL, 0, 0, 15, 16, 2);

--
-- 限制导出的表
--

--
-- 限制表 `gene`
--
ALTER TABLE `gene`
  ADD CONSTRAINT `FKbiodatabase_gene` FOREIGN KEY (`biodatabase_id`) REFERENCES `biodatabase` (`biodatabase_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
