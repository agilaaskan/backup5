-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2021 at 07:35 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ulamart_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `ulamart_cron_log`
--

CREATE TABLE `ulamart_cron_log` (
  `id` int(15) NOT NULL,
  `cron_name` varchar(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `cron_status` varchar(10) NOT NULL,
  `time_exceed` int(11) DEFAULT NULL,
  `time_diff` varchar(50) DEFAULT NULL,
  `is_error` int(10) DEFAULT NULL,
  `email_sent` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ulamart_cron_log`
--

INSERT INTO `ulamart_cron_log` (`id`, `cron_name`, `start_date`, `end_date`, `cron_status`, `time_exceed`, `time_diff`, `is_error`, `email_sent`) VALUES
(1, 'Update Stock Cron', '2021-07-02 04:00:00', '2021-07-02 05:16:00', 'Y', NULL, NULL, NULL, NULL),
(2, 'Product Status Cron', '2021-07-02 02:00:00', '2021-07-02 04:16:00', 'Y', NULL, NULL, NULL, NULL),
(3, 'Product Status Cron', '2021-07-02 19:07:16', '0000-00-00 00:00:00', 'N', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ulamart_cron_log`
--
ALTER TABLE `ulamart_cron_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ulamart_cron_log`
--
ALTER TABLE `ulamart_cron_log`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
