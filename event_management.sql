-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2026 at 06:50 AM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Technology'),
(2, 'Business\r\n'),
(3, 'Education\r\n\r\n'),
(4, 'Sports'),
(5, 'Entertainment');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(150) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `description`, `date`, `time`, `location`, `capacity`, `status`, `organizer_id`, `category_id`) VALUES
(1, 'AIUB Tech Fest 2026', 'AIUB Tech Fest 2026', '2026-09-15', '10:00:00', 'AIUB Campus', 100, 'Upcoming', 1, 1),
(2, 'Bangladesh Tech Innovation Expo\r\n', 'A technology event where students and professionals can explore new ideas, innovative projects and modern digital solutions.', '2026-09-20', '11:00:00', 'Dhanmondi, Dhaka', 300, 'Upcoming', 1, 1),
(3, 'Startup and Entrepreneurship Summit', 'A business event where young entrepreneurs can share startup ideas, learn from experienced professionals and build new connections.', '2026-09-25', '10:30:00', 'Agrabad, Chattogram', 200, 'Upcoming', 1, 2),
(4, ' Future Education Conference', 'An educational conference focused on modern learning methods, digital education and important skills for future careers.', '2026-10-05', '12:00:00', 'Zindabazar, Sylhet', 180, 'Upcoming', 1, 3),
(5, 'University Sports Carnival\r\n', 'A fun sports event featuring football, cricket and other activities for students and sports lovers.\r\n', '2026-10-10', '09:00:00', 'Shaheb Bazar, Rajshahi', 500, 'Upcoming', 1, 4),
(6, 'AI and Robotics Showcase', 'An exciting technology showcase where participants can see artificial intelligence, robotics and innovative student projects.', '2026-10-18', '10:00:00', 'Mirpur, Dhaka', 250, 'Upcoming', 1, 1),
(7, 'Young Business Leaders Meetup', 'A networking event for young people interested in business, leadership and entrepreneurship, with opportunities to share ideas and experiences.', '2026-10-25', '14:00:00', ' Kolatoli, Cox\'s Bazar', 100, 'Upcoming', 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
