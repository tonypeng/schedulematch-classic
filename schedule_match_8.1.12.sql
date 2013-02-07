-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2013 at 08:24 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `schedule_match`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `submission_id` int(10) unsigned NOT NULL,
  `teacher` varchar(45) NOT NULL,
  `period` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `submission_id`, `teacher`, `period`) VALUES
(10, 8, 'asdf', '1'),
(11, 8, 'dsf', '2'),
(12, 8, 'fdasf', '3'),
(13, 9, 'asdf', '1'),
(14, 9, 'asdfasd', '2'),
(15, 9, 'ddd', '3'),
(25, 13, 'math', '1'),
(26, 13, 'literature', '2');

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE IF NOT EXISTS `submissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `password` text NOT NULL,
  `first` varchar(45) NOT NULL,
  `last` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `grade` int(10) unsigned NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `ip`, `password`, `first`, `last`, `email`, `grade`, `date`) VALUES
(8, '127.0.0.1', '4df3d86a246e96ea112e6cb4e3eaec23255048f0', 'aasdf', 'asdf', '', 9, '1343855006'),
(9, '127.0.0.1', 'b832d546b2ea05f6e6e083365d5e7b92113db9e0', 'asdfa', 'asdfasdf', '', 9, '1343855083'),
(13, '127.0.0.1', 'a5b015b2c353e74ad99894021603c382077159c6', 'joe', 'joe', '', 9, '1344106911');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
