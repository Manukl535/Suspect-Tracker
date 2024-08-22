-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2024 at 05:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suspect_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `otp` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `phone`, `otp`) VALUES
(1, 'manu', 'manu@gmail.com', '112233', '+917022015320', '936455'),
(2, 'srisha', 'srisha@gmail.com', '112233', '+917022015320', '717820');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(10) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `file_name`) VALUES
(1, '6.jpg'),
(2, '21.jpg'),
(3, '2.jpg'),
(4, '1.jpg'),
(5, '1.jpg'),
(6, '1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `suspect_details`
--

CREATE TABLE `suspect_details` (
  `suspect_id` varchar(10) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `age` int(10) DEFAULT NULL,
  `address` varchar(30) DEFAULT NULL,
  `image` varchar(20) DEFAULT NULL,
  `prev_crimes` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suspect_details`
--

INSERT INTO `suspect_details` (`suspect_id`, `name`, `age`, `address`, `image`, `prev_crimes`) VALUES
('Sus_01', 'Alice', 23, 'Attibele', 'Alice.png', 'Crime 01:Theft at Attibele Jurisdiction'),
('Sus_02', 'Micheal', 32, 'Koramangala', 'Micheal.png', 'Crime 01: Murder at Banashankari Jurisdiction'),
('Sus_03', 'Manu', 24, 'Attibele', 'manu.jpg', 'Crime 01:Theft at Madivala Jurisdiction'),
('Sus_04', 'Srisha', 20, 'Hosur', 'srisha.jpg', 'Crime 01:Murder at hosaroad jurisdiction');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(10) NOT NULL,
  `file_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `file_name`) VALUES
(1, 'color.mp4'),
(2, 'Gray_scale.mp4'),
(3, 'color.mp4'),
(4, 'Gray_scale.mp4'),
(5, '1.jpg'),
(6, 'color.mp4'),
(7, 'Gray_scale.mp4'),
(8, 'Gray_scale.mp4'),
(9, '1.jpg'),
(10, 'color.mp4'),
(11, 'color.mp4'),
(12, 'color.mp4'),
(13, 'Gray_scale.mp4'),
(14, 'color.mp4'),
(15, 'color.mp4'),
(16, 'color.mp4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suspect_details`
--
ALTER TABLE `suspect_details`
  ADD PRIMARY KEY (`suspect_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
