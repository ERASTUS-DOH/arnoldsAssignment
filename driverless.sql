-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2021 at 01:21 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `driverless`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(10) UNSIGNED NOT NULL,
  `account_username` varchar(20) NOT NULL,
  `account_first_name` varchar(25) NOT NULL,
  `account_last_name` varchar(25) NOT NULL,
  `account_email` varchar(50) NOT NULL,
  `account_password` varchar(255) NOT NULL,
  `account_reg_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_enabled` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `account_username`, `account_first_name`, `account_last_name`, `account_email`, `account_password`, `account_reg_time`, `account_enabled`) VALUES
(5, 'erastusdoh', 'erastus', 'doh', 'erastusdoh@gmail.com', '$2y$10$vsFffX7/vTFb8uMA3na.F.GPhiaVNcOeeBJMDCLX/S9xmnJOd6r0a', '2021-04-21 11:34:18', 0),
(6, 'guvoqapuh', 'Ursa Doyle', 'Iona Lawrence', 'boce@mailinator.com', '$2y$10$I/5fGhwgL/sSsEueLFlZTOgWElQFmiS/RJwnWTXCUhjgDLfIicNBK', '2021-04-21 12:31:55', 0),
(7, 'favulyse', 'Omar Shaw', 'Jorden Franks', 'nocaquzyp@mailinator.com', '$2y$10$M5dpWIM.pa8bPRrrrsILWO.ykDPmzhBnl57UkWdjpmjj4VCrqmL/y', '2021-04-21 12:32:17', 0),
(8, 'fufebupyla', 'Yuri Travis', 'Lyle Lopez', 'vusybowad@mailinator.com', '$2y$10$xC5fVbJtsOOENdVwf7Nd7.i4C3.7nwDBE3.5OXYCJLYrK.iDuPQ62', '2021-04-21 12:34:42', 0),
(9, 'xehegyhuq', 'Darrel Francis', 'Lilah William', 'tucogog@mailinator.com', '$2y$10$A09DltrpT9D09LzPpknsquPETEcV1Dkbb6SyJmK06FCglaxioSu3S', '2021-04-21 12:35:07', 0),
(10, 'zijiduhyg', 'Charissa Middleton', 'Tanya Hawkins', 'kiriwobux@mailinator.com', '$2y$10$h5udO./6RjLQkZUj.yT5kejma1P076oPlMywhwy6HQboWOAavn/h.', '2021-04-21 12:40:31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `account_sessions`
--

CREATE TABLE `account_sessions` (
  `session_id` varchar(225) NOT NULL,
  `account_id` int(10) UNSIGNED NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_sessions`
--

INSERT INTO `account_sessions` (`session_id`, `account_id`, `login_time`) VALUES
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 11:36:33'),
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 11:37:23'),
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 11:39:16'),
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 11:42:17'),
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 11:44:23'),
('9neab6leokrd86j9d11tev7emk', 6, '2021-04-21 12:31:55'),
('9neab6leokrd86j9d11tev7emk', 7, '2021-04-21 12:32:17'),
('9neab6leokrd86j9d11tev7emk', 8, '2021-04-21 12:34:42'),
('9neab6leokrd86j9d11tev7emk', 9, '2021-04-21 12:35:07'),
('9neab6leokrd86j9d11tev7emk', 10, '2021-04-21 12:40:31'),
('9neab6leokrd86j9d11tev7emk', 5, '2021-04-21 12:50:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_password` (`account_password`),
  ADD UNIQUE KEY `account_email` (`account_email`);

--
-- Indexes for table `account_sessions`
--
ALTER TABLE `account_sessions`
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
