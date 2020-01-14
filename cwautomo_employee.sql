-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 14, 2020 at 09:34 AM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cwautomo_employee`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `memberID` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `admin` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `active` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `resetToken` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `resetComplete` varchar(3) COLLATE utf8mb4_bin DEFAULT 'No'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `memberID`, `admin`, `username`, `password`, `email`, `active`, `resetToken`, `resetComplete`) VALUES
(0, '123', 'Yes', 'admin', '$2y$10$JepBzUvw/lUpul1LBPdAO.wBYxWo1mE0Ytk8rfgJYJlkv96oMfYUC', 'email@email.com', 'Yes', NULL, 'No');
-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL,
  `submit_day` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `time_in` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_out` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_hours` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `submit_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `approve_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `change_request` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_comments` text COLLATE utf8_unicode_ci NOT NULL,
  `pay_period` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `timesheet`
--

INSERT INTO `timesheet` (`id`, `submit_day`, `user_id`, `name`, `time_in`, `time_out`, `total_hours`, `submit_status`, `approve_status`, `change_request`, `request_comments`, `pay_period`) VALUES
(0, '2019-09-18', '123', 'admin', '08:00', '17:00', '9', 'Submitted on Sep,18,2019', 'Approved', NULL, '', ''),
(1, '2019-09-23', '123', 'admin', '07:17', '17:17', '10', 'Submitted on Sep,23,2019', 'Approved', '', 'Change to: Time in: 07:17 Time out: 17:17 Total Hours: 10', ''),
(2, '2019-10-23', '123', 'admin', '09:00', '17:00', '8', 'Submitted on Oct,01,2019', NULL, NULL, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
