-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 10, 2021 at 04:48 AM
-- Server version: 5.6.51
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ulamart_ne2m2`
--

-- --------------------------------------------------------

--
-- Table structure for table `ps_product_extra_weight`
--

CREATE TABLE `ps_product_extra_weight` (
  `id` int(11) NOT NULL,
  `product_id` varchar(100) NOT NULL,
  `attribute_id` varchar(100) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `net_weight` varchar(100) NOT NULL,
  `extra_weight` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ps_product_extra_weight`
--

INSERT INTO `ps_product_extra_weight` (`id`, `product_id`, `attribute_id`, `product_name`, `net_weight`, `extra_weight`) VALUES
(1, '408', '135', 'HILL HONEY 1/2kg', '0.500000', '0.430'),
(4, '413', '128', 'tulsi-honey 1kg', '0.100000', '0.350'),
(3, '409', '136', 'HILL HONEY 1kg', '1.000000', '0.350'),
(5, '412', '130', 'tulsi-honey 1/2kg', '0.500000', '0.430'),
(6, '417', '123', 'neem-honey 1kg', '0.100000', '0.350'),
(7, '416', '125', 'neem-honey 1/2kg', '0.500000', '0.430'),
(8, '421', '118', 'naaval-honey 1kg', '0.100000', '0.350'),
(9, '420', '120', 'naaval-honey 1/2kg', '0.500000', '0.430'),
(10, '425', '113', 'drumstick-honey 1kg', '0.100000', '0.350'),
(11, '424', '115', 'drumstick-honey 1/2kg', '0.500000', '0.430'),
(12, '837', '502', 'multi-flower-farm-honey 1kg', '0.100000', '0.350'),
(13, '836', '501', 'multi-flower-farm-honey 1/2kg', '0.500000', '0.430'),
(14, '557', '367', 'vathal 1kg', '0.100000', '0.100'),
(15, '555', '368', 'vathal 1/4kg', '0.250000', '0.100'),
(16, '562', '370', 'vathal 1kg', '0.100000', '0.100'),
(17, '560', '369', 'vathal 1/4kg', '0.250000', '0.100'),
(18, '790', '490', 'vathal 1kg', '0.100000', '0.100'),
(19, '788', '489', 'vathal 1/4kg', '0.250000', '0.100'),
(20, '392', '143', 'virgin-coconut-oil 1kg', '1.000000', '0.100'),
(21, '396', '140', 'sesame-oil 1kg', '1.000000', '0.100'),
(22, '400', '137', 'groundnut-oil 1kg\r\n', '1.000000', '0.100'),
(23, '930', '', 'vathal 1/4kg', '0.250000', '0.100'),
(24, '362', '', 'Aloevera-Cucumber-Moringa Handmade Soaps (Pack of 2)', '0.200', '0.100'),
(25, '363', '', 'ORANGE PEEL HANDMADE SOAPS (PACK OF 2)', '0.200', '0.100'),
(26, '364', '', 'VETTIVERU SOAP - HANDMADE (PACK OF 2)', '0.200', '0.100'),
(27, '365', '', 'Carrot Soap Handmade (Pack of 2)', '0.200', '0.100'),
(28, '366', '', 'GREEN GRAM SOAP (PACK OF 2)', '0.200', '0.100'),
(29, '367', '', 'Activated Charcoal Facial Soap (Pack of 2)', '0.200', '0.100'),
(30, '368', '', 'Aloevera Neem Soap (Pack of 2) |Handmade Herbal Bath Soaps', '0.200', '0.100'),
(31, '370', '', 'SHIKAKAI HERBAL SOAP FOR HAIR- HANDMADE (PACK OF 2)', '0.200', '0.100'),
(32, '371', '', 'NALANGU MAAVU HERBAL HANDMADE SOAPS (PACK OF 2)', '0.200', '0.100'),
(33, '372\r\n', '', 'MULTANI MITTI HANDMADE HERBAL SOAP (PACK OF 2)', '0.200', '0.100');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ps_product_extra_weight`
--
ALTER TABLE `ps_product_extra_weight`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ps_product_extra_weight`
--
ALTER TABLE `ps_product_extra_weight`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
