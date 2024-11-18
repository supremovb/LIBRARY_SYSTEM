-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2024 at 06:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `published_date` date NOT NULL,
  `status` enum('available','borrowed','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'available',
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `description`, `author`, `isbn`, `published_date`, `status`, `photo`, `created_at`, `updated_at`) VALUES
(15, 'ILYS1892 (I LOVE YOU SINCE 1892)', 'From the synopsis in Wattpad, the book tells the story of Carmela, a girl from 2016 who gets sent back to the year 1892 and will fall in love with Juanito, a doctor-to-be and son of a powerful gobernadorcillo. The laws of nature will bend.', 'Binibining Mia', '9789718162408', '2018-01-18', 'available', '1731850657_ac40076fcaedda802a91.jpg', '2024-11-17 12:40:42', '2024-11-18 15:40:27'),
(16, 'The Rain In España', '“The Rain in España” follows the story of Kalix and Luna, two college freshmen who fall in love despite the pressures of school, family, and ambition. However, their relationship takes a turn for the worse when Luna discovers Kalix\'s infidelity, leading to a bitter break-up.', 'Gwy Saludes', '9786210309171', '2022-05-17', 'available', '1731849782_13863b7a2d433c4e6bf4.jpg', '2024-11-17 13:23:02', '2024-11-18 09:56:02'),
(17, 'TALK BACK AND YOU\'RE DEAD', 'After an encounter with the school\'s leader, Samantha accidentally gets death threats from the elite leader TOP whom she unexpectedly crossed paths with again later on. She must bow down to his list of needs or he will take on her life.', 'Alesana Marie', '4806518075594', '2013-11-17', 'available', '1731850276_90d1e77b0551f556e2ad.jpg', '2024-11-17 13:31:16', '2024-11-18 15:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`) VALUES
(2, 28, '203d39dee5dbe008b7f1129825cdd9a7532d3b6b4ebeea360315087df445b5135e40b7e2afe17acab327d8265a07943f7fa4', '2024-11-18 17:21:58'),
(3, 28, '2db5be2dfddc189716037ad16e83d6a6a11509490f2bcee69b44e3804354523773325e473f4ed112d5e4293ce3521c62a8fa', '2024-11-18 17:22:23'),
(4, 28, '8ab2a6da3546bc6dc6c9cdeff1f5c80a1a42558842306518a8ecaf6c4243bd658d6af67dca078e58c58aa859f4c4205c4361', '2024-11-18 17:23:32'),
(5, 28, 'a244ad003b03a013ddd80e27194a425cffa21f15e66453e715be3733424ac774e266455787feaf76bc6e5f8d04842ff7c3f3', '2024-11-18 17:26:28'),
(6, 28, '14ad9269ace9dabd7d16c2f3bc1db2916b5e27a77f4a2e3b3195a36e8f870c1679a1fdbf7869373620ab3eec8e681a88691b', '2024-11-18 17:29:19'),
(9, 28, '1bb1e6291c6d656e4f3232f9a0a60c5f1aff88cd9b95766991abaa9f788428c0d657fce4310d670582b2d0817f4079e7d436', '2024-11-18 16:46:22'),
(10, 28, 'bde6c1c7346cfb6039487e5dd9679931ad135f986d3c669ab78eff7ca6ca6248af99f7b4c6b54f98fb939616858428a95650', '2024-11-18 16:46:45'),
(11, 28, '3294202471956c87df53333e2ed674c44aff26d8125821c60515822f44e5cfe1dae29de1c03ae0ede1aab83ce47435479745', '2024-11-18 16:48:56'),
(12, 28, '50a9fedefdd4357b27957c7ec2fd7606675c63fd1f978a348e0e5e30faa44b7836a820d8ad81cbfba6a880c79284609d17c2', '2024-11-18 16:50:00'),
(13, 28, 'd7c2c5f82364d85d46194625fac74f10504e9819b91db18432577e045a7fa3d7f967a6140d69f07308150e8f990ce9523837', '2024-11-18 16:50:02'),
(14, 28, '71d6264babc6bfc76912dd40b5f1c2b0711542217a73c967b2f570afae6711b0ffcca4dd4e127a8a4f26efa2357a90a14da4', '2024-11-18 16:50:03'),
(15, 28, '4f40bb54d5ac81ce231169e0207476d50959156163f9a117a703af20b596956c6e4fd28f8fff0bf85a26c48c3d03bf6c9ab3', '2024-11-18 16:50:05'),
(19, 28, '0cee0ccbd5f3ca7e8c4a8280024a27aeddecfb6e01586a4cec27987ac281e60821a4a426762cf5cd24ca0cbadc788084efb0', '2024-11-18 17:04:52');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int NOT NULL,
  `book_id` int NOT NULL,
  `user_id` int NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` enum('borrowed','returned','pending','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'borrowed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `book_id`, `user_id`, `borrow_date`, `return_date`, `due_date`, `remarks`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 15, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 07:08:54', '2024-11-17 07:09:06', NULL),
(6, 15, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 07:12:44', '2024-11-17 07:12:50', NULL),
(7, 15, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 07:54:24', '2024-11-17 07:54:44', NULL),
(8, 16, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 07:55:14', '2024-11-17 07:55:18', NULL),
(9, 16, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 08:03:21', '2024-11-17 08:19:48', NULL),
(10, 15, 20, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 08:15:03', '2024-11-17 08:19:57', NULL),
(11, 15, 22, '2024-11-17', '2024-11-17', NULL, NULL, 'returned', '2024-11-17 08:21:31', '2024-11-17 08:21:37', NULL),
(12, 15, 22, '2024-11-17', NULL, '2024-11-19', NULL, 'borrowed', '2024-11-17 08:26:50', '2024-11-18 01:39:32', NULL),
(13, 15, 22, '2024-11-17', NULL, NULL, NULL, 'rejected', '2024-11-17 08:30:11', '2024-11-18 01:45:39', NULL),
(14, 16, 20, '2024-11-17', '2024-11-18', '2024-11-19', NULL, 'returned', '2024-11-17 10:06:22', '2024-11-18 01:50:33', NULL),
(15, 15, 20, '2024-11-18', NULL, NULL, NULL, 'rejected', '2024-11-18 01:48:53', '2024-11-18 01:55:06', NULL),
(16, 15, 20, '2024-11-18', '2024-11-18', '2024-11-19', NULL, 'returned', '2024-11-18 01:55:20', '2024-11-18 02:14:47', NULL),
(17, 16, 20, '2024-11-18', NULL, NULL, NULL, 'rejected', '2024-11-18 01:55:24', '2024-11-18 01:56:02', NULL),
(18, 15, 20, '2024-11-18', '2024-11-19', '2024-11-18', NULL, 'returned', '2024-11-18 02:22:42', '2024-11-19 02:23:43', NULL),
(19, 15, 20, '2024-11-18', '2024-11-18', '2024-11-18', NULL, 'returned', '2024-11-18 02:25:20', '2024-11-18 02:25:50', NULL),
(20, 15, 20, '2024-11-18', '2024-11-18', '2024-11-18', 'On time', 'returned', '2024-11-18 02:28:10', '2024-11-18 02:28:44', NULL),
(21, 17, 20, '2024-11-18', '2024-11-19', '2024-11-18', 'Late', 'returned', '2024-11-18 02:28:55', '2024-11-19 02:29:20', NULL),
(22, 17, 27, '2024-11-18', '2024-11-18', '2024-11-19', 'On time', 'returned', '2024-11-18 07:10:29', '2024-11-18 07:12:32', NULL),
(23, 15, 27, '2024-11-18', '2024-11-18', '2024-11-19', 'On time', 'returned', '2024-11-18 07:38:49', '2024-11-18 07:40:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year` varchar(10) NOT NULL,
  `role` enum('admin','student') NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `firstname`, `lastname`, `course`, `year`, `role`, `photo`, `created_at`, `updated_at`) VALUES
(20, 'supremopkv', '$2y$10$357Zqcz.UWCqRbdsc0zTsep4.g46ah7kK4gS.Nt8hob4igXcE41/G', '', 'Primo', 'Velasquez', 'BSIT', '3RD YEAR', 'student', 'http://localhost/library_system/uploads/user_photos/1731934459_91c9eee6eb50822937ac.jpg', '2024-11-15 18:58:26', '2024-11-18 05:54:38'),
(21, 'mervin', '$2y$10$s3w/edvC817lkSiDpOcym.uDAUfpf.SK4enXlrPa.zvA4PKTSYcQW', '', 'Mervin', 'Babiano', 'admin1', 'admin1', 'admin', 'http://localhost/library_system/uploads/user_photos/1731937374_40824f0c87a06969cae2.jpg', '2024-11-15 19:00:49', '2024-11-18 07:39:55'),
(22, 'chastinita', '$2y$10$mikRYDtTvr9b04cDvGa3penQIgzRhk1.50ogFeulbaZ0SQb1PpIuG', '', 'Chastine Kyle', 'Melgarejo', 'MASCOM', '4th Year', 'student', 'http://localhost/library_system/uploads/user_photos/1731940205_11c8d821c6268dd46816.jpg', '2024-11-17 16:21:10', '2024-11-18 06:30:16'),
(26, 'alabado', '$2y$10$5iIH2u/DsIDIxnllY6h/IeGmTtIEXeJULHwlR2uwshFTZgzOjSAGq', '', 'Arriane', 'Alabado', 'BSIT', '3rd Year', 'student', NULL, '2024-11-18 15:08:25', '2024-11-18 15:08:25'),
(27, 'monton', '$2y$10$PiOe/lEFgh3QHDzDfvllnuVMQKI.YuZeXMjQDa23xdNi1PtEKSOmO', '', 'Jirielle', 'Monton', 'BSIT', '3rd Year', 'student', NULL, '2024-11-18 15:09:32', '2024-11-18 07:40:46'),
(28, 'padilla', '$2y$10$N61HIqwE5mkhpp0T/bN/w.auscRXhlnHw4UUZ7BhPu3TkOH07Iu56', 'chastinemylove@gmail.com', 'Daniel', 'Padilla', 'BSIT', '3rd Year', 'student', NULL, '2024-11-18 16:15:56', '2024-11-18 08:57:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
