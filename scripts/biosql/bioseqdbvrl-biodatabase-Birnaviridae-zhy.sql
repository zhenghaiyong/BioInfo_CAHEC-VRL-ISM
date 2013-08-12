-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 08 月 12 日 19:24
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
-- 表的结构 `biodatabase`
--

CREATE TABLE IF NOT EXISTS `biodatabase` (
  `biodatabase_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL,
  `authority` varchar(128) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`biodatabase_id`),
  UNIQUE KEY `name` (`name`),
  KEY `db_auth` (`authority`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `biodatabase`
--

INSERT INTO `biodatabase` (`biodatabase_id`, `name`, `authority`, `description`) VALUES
(1, 'Birnaviridae', NULL, NULL);
