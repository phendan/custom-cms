-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2022 at 02:28 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `slug` varchar(64) NOT NULL,
  `body` text NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `slug`, `body`, `created_at`) VALUES
(2, 9, 'My New Test Post', 'my-new-test-post', 'asdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdfasdf', 1658776768),
(4, 9, 'Another Test Post', 'another-test-post', 'double opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdfdouble opt inasdfasdf', 1658855682),
(6, 10, 'My Test Post', 'my-test-post', 'My Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test PostMy Test Post', 1658862525),
(7, 9, 'New Post Title', 'new-post-title', 'New Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post TitleNew Post Title', 1659381344),
(8, 9, 'My Custom Post', 'my-custom-post', 'My Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom PostMy Custom Post', 1659381939),
(15, 10, 'Test title &lt;script&gt;alert(\'hacked\');&lt;/script&gt;', 'test-title-scriptalerthackedscript', 'Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title Test title', 1659469394);

-- --------------------------------------------------------

--
-- Table structure for table `post_images`
--

CREATE TABLE `post_images` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_images`
--

INSERT INTO `post_images` (`id`, `post_id`, `filename`, `created_at`) VALUES
(6, 15, '27f2891df38ea093cf9e37b6ba5dfaea.jpg', 1659469394);

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(10, 8, 9, 1659467827),
(11, 8, 10, 1659467860);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(64) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `first_name`, `last_name`, `password`) VALUES
(9, 'p.braunen@sae.edu', 'Philip', 'Braunen', '$2y$10$cV80OzEo2kQK35SfqjqdXeoqFfAle6pgRVUuDPJ6dbwgJgKlzPIp6'),
(10, 'test@test.test', 'Fernando', 'Sabiu', '$2y$10$l7nD3fHijEIAR3ioJ1dKa.c4loXHbYUC96PHVT1Xi8/Htr5nAMmJC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id_2` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

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
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `post_images_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
