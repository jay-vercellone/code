-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 05, 2017 at 12:46 AM
-- Server version: 5.6.32-78.1-log
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `localle2_apitool`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertiserapi`
--

CREATE TABLE IF NOT EXISTS `advertiserapi` (
  `AdvertiserAPIID` int(11) unsigned NOT NULL,
  `AdvertiserID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AdvertiserStatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AdvertiserName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LastUpdate` datetime NOT NULL,
  `OfferStatusUpdate` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hooffers`
--

CREATE TABLE IF NOT EXISTS `hooffers` (
  `ID` int(11) unsigned NOT NULL,
  `OfferID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AdvertiserID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferStatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `RefID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `LastUpdate` datetime NOT NULL,
  `OfferURL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferPause` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `TempCol` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `HOCreated` datetime NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=23124 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE IF NOT EXISTS `offers` (
  `ID` int(11) unsigned NOT NULL,
  `OfferID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `AdvertiserID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferDescription` text COLLATE utf8_unicode_ci NOT NULL,
  `OfferStatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `PreviewURL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `OfferURL` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `DefaultPayout` text COLLATE utf8_unicode_ci NOT NULL,
  `PayoutType` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ConversionCap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Incent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Country` text COLLATE utf8_unicode_ci NOT NULL,
  `OperatingSystem` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `RequireApproval` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ExpiryDate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Created` date NOT NULL,
  `LastUpdate` datetime NOT NULL,
  `showinapp` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=2078309 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertiserapi`
--
ALTER TABLE `advertiserapi`
  ADD PRIMARY KEY (`AdvertiserAPIID`);

--
-- Indexes for table `hooffers`
--
ALTER TABLE `hooffers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertiserapi`
--
ALTER TABLE `advertiserapi`
  MODIFY `AdvertiserAPIID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=118;
--
-- AUTO_INCREMENT for table `hooffers`
--
ALTER TABLE `hooffers`
  MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23124;
--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2078309;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
