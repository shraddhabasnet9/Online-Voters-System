-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 03:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(11) NOT NULL,
  `election_id` int(11) DEFAULT NULL,
  `cname` varchar(50) NOT NULL,
  `symbol` varchar(50) NOT NULL,
  `symphoto` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `tvotes` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `election_id`, `cname`, `symbol`, `symphoto`, `position`, `tvotes`) VALUES
(1, 7, 'virat', 'cricket', 'symbol/1761491170_20220113_140416.jpg', 'Vice Mayor', 0),
(5, 7, 'shraddha basnet', 'tree', 'symbol/1761574397_GanttChart_voting.png', 'Vice Mayor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `can_position`
--

CREATE TABLE `can_position` (
  `id` int(255) NOT NULL,
  `position_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `can_position`
--

INSERT INTO `can_position` (`id`, `position_name`) VALUES
(3, 'Mayor'),
(4, 'Vice Mayor'),
(5, 'Adyakxya'),
(6, 'Vice Adyakxya'),
(12, 'Sadasya');

-- --------------------------------------------------------

--
-- Table structure for table `phno_change`
--

CREATE TABLE `phno_change` (
  `id` int(255) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `idname` varchar(20) NOT NULL,
  `idcard` varchar(300) NOT NULL,
  `dob` varchar(50) NOT NULL,
  `old_phno` varchar(15) NOT NULL,
  `new_phno` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `idname` varchar(50) NOT NULL,
  `idnum` varchar(50) NOT NULL,
  `idcard` varchar(300) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `verify` varchar(10) NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `name`, `address`, `idname`, `idnum`, `idcard`, `dob`, `gender`, `phone`, `email`, `verify`, `status`) VALUES
(106, 'santosh', 'jhapa', 'national id', '12345', 'img/0Screenshot (11).png', '2000-01-12', 'male', '1234512345', 'santosh@gmail.com', '', 'not voted');

-- --------------------------------------------------------

--
-- Table structure for table `vote_title`
--

CREATE TABLE `vote_title` (
  `id` int(11) NOT NULL,
  `voting_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vote_title`
--

INSERT INTO `vote_title` (`id`, `voting_title`) VALUES
(1, 'bidyarthi sangathan'),
(2, 'abc trek chunab');

-- --------------------------------------------------------

--
-- Table structure for table `voting`
--

CREATE TABLE `voting` (
  `id` int(11) NOT NULL,
  `vote_title_id` int(11) NOT NULL,
  `vot_start_date` datetime NOT NULL,
  `vot_end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voting`
--

INSERT INTO `voting` (`id`, `vote_title_id`, `vot_start_date`, `vot_end_date`) VALUES
(7, 1, '2025-10-27 22:55:00', '2025-10-28 20:56:00');

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
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `symbol` (`symbol`),
  ADD UNIQUE KEY `unique_candidate_entry` (`cname`,`election_id`,`symbol`,`position`),
  ADD KEY `election_id` (`election_id`);

--
-- Indexes for table `can_position`
--
ALTER TABLE `can_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phno_change`
--
ALTER TABLE `phno_change`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `idnum` (`idnum`);

--
-- Indexes for table `vote_title`
--
ALTER TABLE `vote_title`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voting`
--
ALTER TABLE `voting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vote_title_id` (`vote_title_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `can_position`
--
ALTER TABLE `can_position`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `phno_change`
--
ALTER TABLE `phno_change`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `vote_title`
--
ALTER TABLE `vote_title`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `voting`
--
ALTER TABLE `voting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`election_id`) REFERENCES `voting` (`id`);

--
-- Constraints for table `voting`
--
ALTER TABLE `voting`
  ADD CONSTRAINT `voting_ibfk_1` FOREIGN KEY (`vote_title_id`) REFERENCES `vote_title` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
