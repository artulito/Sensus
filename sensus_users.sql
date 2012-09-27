-- phpMyAdmin SQL Dump
-- version 2.7.0-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Feb 17, 2008 at 09:41 AM
-- Server version: 5.0.18
-- PHP Version: 5.1.1
-- 
-- Database: `sensus_rekap`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `sdj_users`
-- 

CREATE TABLE `sdj_users` (
  `id` int(10) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `last_login` varchar(40) default NULL,
  `passkey` varchar(40) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sdj_users`
-- 