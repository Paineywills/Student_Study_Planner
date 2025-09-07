-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 04:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

<<<<<<< HEAD
=======

>>>>>>> fc9c903b65637d47bab2cb2e714c35915f221939
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

<<<<<<< HEAD
-- --------------------------------------------------------
-- Create database and use it
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `student_study_planner`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `student_study_planner`;
=======
--
-- Database: `student_study_planner`
--

-- --------------------------------------------------------

>>>>>>> fc9c903b65637d47bab2cb2e714c35915f221939
--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `priority` enum('High','Medium','Low') DEFAULT 'Medium',
  `status` enum('pending','done') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `due_date`, `priority`, `status`, `created_at`) VALUES
(25, 2, 'Ass', 'ass', '2025-08-13', 'High', 'done', '2025-08-22 23:22:44'),
(26, 2, 'Practice Problems', 'Solve 10 exercises', NULL, 'High', 'done', '2025-08-23 16:45:17'),
(27, 2, 'Flashcards', 'Make flashcards for key terms', NULL, 'Low', 'done', '2025-08-23 16:45:17'),
(28, 2, 'Group Study', 'Study with classmates for 1 hour', NULL, 'Medium', 'done', '2025-08-23 16:45:17'),
(29, 2, 'Read Chapter 1', 'Introduction to subject', NULL, 'Medium', 'done', '2025-08-28 11:57:33'),
(30, 2, 'Read Chapter 1', 'Introduction to subject', NULL, 'Medium', 'done', '2025-08-28 11:57:36'),
(31, 2, 'Review Notes', 'Go through last lecture notes', NULL, 'High', 'done', '2025-08-28 11:57:37'),
(32, 2, 'Practice Problems', 'Solve 10 exercises', NULL, 'High', 'done', '2025-08-28 11:57:37'),
(33, 2, 'Check References', 'Update bibliography list', NULL, 'Low', 'done', '2025-08-28 11:57:38'),
(34, 2, 'Review Notes', 'Go through last lecture notes', NULL, 'High', 'done', '2025-08-28 11:58:03'),
(35, 2, 'Read Chapter 1', 'Introduction to subject', NULL, 'Medium', 'done', '2025-08-28 12:04:44'),
(36, 2, 'Set Goals', 'Write 3 learning goals for tomorrow', NULL, 'Medium', 'done', '2025-08-28 12:04:46'),
(37, 2, 'Set Goals', 'Write 3 learning goals for tomorrow', NULL, 'Medium', 'done', '2025-08-28 12:04:47'),
(38, 2, 'Read Chapter 1', 'Introduction to subject', NULL, 'Medium', 'done', '2025-08-28 12:04:47'),
(39, 2, 'Read Chapter 1', 'Introduction to subject', NULL, 'Medium', 'done', '2025-08-28 12:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(160) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'ZJEKE', 'paineywills1526@gmail.com', '$2y$10$JXVBgGEYuhJKqwyCWIWq6.0oj7RTEkM2oVGqLsZFh1N7fY1XPmLuC', '2025-08-22 21:05:05'),
(2, 'USER', 'markmac1526@gmail.com', '$2y$10$9qjH1Tava89YWOpd3.7/jOxBcY9RvbzInxHOPCyA/01liNlKAflcm', '2025-08-22 21:14:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
