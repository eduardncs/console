-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 04, 2020 at 08:36 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Rosance_15397314355f3d7d05a7f80`
--

-- --------------------------------------------------------

--
-- Table structure for table `Authors`
--

CREATE TABLE `Authors` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `UID` varchar(32) NOT NULL,
  `profile_pic` text NOT NULL,
  `First_Name` varchar(32) NOT NULL,
  `Last_Name` varchar(32) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Optional` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Authors`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `Category_Name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `Comments`
--

CREATE TABLE `Comments` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `PostID` bigint(20) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `Username` varchar(64) NOT NULL,
  `Content` text NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Comments`
--

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `Title` text NOT NULL,
  `Thumbnail` text NOT NULL,
  `Author` varchar(64) NOT NULL,
  `Categories` text NOT NULL,
  `Content` varchar(32) NOT NULL,
  `Date` datetime NOT NULL,
  `allow_comments` varchar(5) NOT NULL,
  `allow_featured` varchar(5) NOT NULL,
  `seo_slug` text NOT NULL,
  `seo_title` text NOT NULL,
  `seo_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `Date` datetime NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Subject` text NOT NULL,
  `Message` text NOT NULL,
  `Ip` varchar(20) NOT NULL,
  `hasRead` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Posts`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Authors`
--
ALTER TABLE `Authors`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Comments`
--
ALTER TABLE `Comments`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `Posts`
--
ALTER TABLE `Posts`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Authors`
--
ALTER TABLE `Authors`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `Comments`
--
ALTER TABLE `Comments`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
