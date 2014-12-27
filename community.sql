-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2014 at 06:41 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `community`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `commented_by` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `unlikes` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=344 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `commented_by`, `likes`, `unlikes`, `time`, `comment`) VALUES
(327, 153, 1, 0, 0, 1418662200, 'df'),
(328, 157, 1, 0, 0, 1418673897, 'hh'),
(329, 159, 1, 0, 0, 1418675795, 'yah'),
(330, 159, 1, 0, 0, 1418749617, 'duh'),
(331, 159, 1, 0, 0, 1418749673, 'kha'),
(332, 159, 1, 0, 0, 1418749789, 'sala'),
(333, 159, 1, 0, 0, 1418749921, 'baal'),
(334, 159, 1, 0, 0, 1418754335, 'hello'),
(335, 159, 1, 0, 0, 1418754698, 'huu'),
(336, 159, 1, 0, 0, 1418754708, 'hira'),
(337, 159, 1, 0, 0, 1418754800, 'hello'),
(338, 159, 1, 0, 0, 1418754817, 'yoo'),
(339, 159, 1, 0, 0, 1418755049, 'haha'),
(340, 159, 1, 0, 0, 1418755077, 'hola'),
(341, 159, 1, 0, 0, 1418755136, 'fucker'),
(342, 159, 1, 0, 0, 1418755144, ' feel'),
(343, 159, 1, 0, 0, 1418755160, ' the rythm');

-- --------------------------------------------------------

--
-- Table structure for table `comment_dislikes`
--

CREATE TABLE IF NOT EXISTS `comment_dislikes` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `comment_dislikes`
--

INSERT INTO `comment_dislikes` (`id`, `user_id`, `comment_id`) VALUES
(23, 18, 124),
(24, 18, 126);

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE IF NOT EXISTS `comment_likes` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=53 ;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `user_id`, `comment_id`) VALUES
(47, 18, 121),
(48, 18, 122),
(49, 18, 123),
(50, 18, 125),
(51, 18, 291),
(52, 1, 308);

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE IF NOT EXISTS `connections` (
`id` int(11) NOT NULL,
  `person_1` int(11) NOT NULL,
  `person_2` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`id`, `person_1`, `person_2`) VALUES
(3, 18, 1),
(4, 19, 18),
(7, 20, 18);

-- --------------------------------------------------------

--
-- Table structure for table `connection_requests`
--

CREATE TABLE IF NOT EXISTS `connection_requests` (
`id` int(11) NOT NULL,
  `sent_from` int(11) NOT NULL,
  `sent_to` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Dumping data for table `connection_requests`
--

INSERT INTO `connection_requests` (`id`, `sent_from`, `sent_to`) VALUES
(69, 20, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL,
  `posted_by` int(11) NOT NULL,
  `posted_to` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `unlikes` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `random_string` text NOT NULL,
  `post` text NOT NULL,
  `type` smallint(6) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=160 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `posted_by`, `posted_to`, `likes`, `unlikes`, `time`, `random_string`, `post`, `type`) VALUES
(148, 1, 1, 0, 0, 1418661123, '175fa6613a0407e6964d2942fde8acf0', 'hey ', 1),
(149, 20, 20, 0, 0, 1418661182, '362206608ff72122450cda269b1258bd', 'my baby', 1),
(150, 20, 1, 0, 0, 1418661334, '789afaa4517232cac3cd2cd80922882e', 'Sexy how are ya? :v :v', 1),
(151, 20, 1, 0, 0, 1418661750, 'd542afc3ad2e318eaeb4db518b7b95d2', ' hii', 1),
(152, 20, 1, 0, 0, 1418661842, '6b92c98e222ce78776b8fc05d7b4cc63', ' sdsdsd', 1),
(153, 20, 1, 0, 0, 1418661976, '98eaab4c0f85a532f31ac8d50213e098', ' Baaaaaaaaaaaaaaaal', 1),
(154, 20, 1, 0, 0, 1418662225, '9e0b4536fd4a9f734db7ab435e233481', 'Tom jerry', 1),
(155, 20, 1, 0, 0, 1418668809, '4270115292dba017a26a1d57f751f4f7', 'hello i am tom.. :p', 1),
(156, 1, 1, 0, 0, 1418668880, 'c0a27ee410e310abd42c94448a80708f', 'hIIIIIIIII', 1),
(157, 1, 1, 0, 0, 1418670007, '2347b25b9673be23435dc4a7df39b16a', 'Hayy', 1),
(158, 1, 1, 0, 0, 1418674079, 'eae2a13a4eb6a21d4a92e1511884ea5c', 'lol', 1),
(159, 1, 1, 0, 0, 1418675608, 'eae4f6ae5aad5fb00c79abd00baa88c8', 'hey', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_dislikes`
--

CREATE TABLE IF NOT EXISTS `post_dislikes` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `post_dislikes`
--

INSERT INTO `post_dislikes` (`id`, `user_id`, `post_id`) VALUES
(13, 18, 130),
(14, 1, 139),
(15, 20, 140);

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE IF NOT EXISTS `post_likes` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`) VALUES
(26, 21, 128),
(27, 18, 128);

-- --------------------------------------------------------

--
-- Table structure for table `realtime_data_counter`
--

CREATE TABLE IF NOT EXISTS `realtime_data_counter` (
  `comment` int(11) NOT NULL,
  `connection` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `realtime_data_counter`
--

INSERT INTO `realtime_data_counter` (`comment`, `connection`) VALUES
(1418656357, 120);

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE IF NOT EXISTS `user_login` (
`id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `username` varchar(30) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user_login`
--

INSERT INTO `user_login` (`id`, `email`, `password`, `username`) VALUES
(1, 'saaggy18@gmail.com', '5a087805df44cc4394fb462b85020695', 'saaggy18'),
(18, 'puja@gmail.com', '5a087805df44cc4394fb462b85020695', 'puja'),
(19, 'cr7@gmail.com', '5a087805df44cc4394fb462b85020695', 'cristiano_cr7'),
(20, 'tom@gmail.com', '5a087805df44cc4394fb462b85020695', 'tomm'),
(21, 'jerry@gmail.com', '5a087805df44cc4394fb462b85020695', 'jerry');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE IF NOT EXISTS `user_profile` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `birth_city` varchar(255) NOT NULL,
  `current_city` varchar(255) NOT NULL,
  `school` varchar(1000) NOT NULL,
  `college` varchar(1000) NOT NULL,
  `website` text NOT NULL,
  `about` text NOT NULL,
  `avatar` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `user_id`, `name`, `birth_city`, `current_city`, `school`, `college`, `website`, `about`, `avatar`) VALUES
(9, 1, 'Sagnik Chakraborti', 'Kolkata, India', 'Bangalore, India', 'SPHS', 'NSEC', 'No website', 'Nothing about me is required to tell.', '1.jpg'),
(10, 18, 'Puja Singh Bhim ', 'Dhanbad, India', 'Ahmedabad, India', 'DGHS', 'NSEC', 'No website', 'I am Puja Singh.. duhh', '18.jpg'),
(11, 19, 'Cristiano Ronaldo', 'You are from which city? (Calcutta, India)?', 'Living at (Silicon Valley, California)?', 'School?', 'College, University?', 'My website/blog (full url)?', 'Say something about yourself?', '19.jpg'),
(12, 20, 'Tom Cat', 'Hollywood, California', 'Hollywood, California', 'None', 'None', 'No website', 'I am Tom. Enough Said. :p', '20.jpg'),
(13, 21, 'Jerry Mouse', 'Hollywood, California', 'Hollywood, California', 'None', 'None', 'None', 'I am Jerry Mouse. Tom is my best friend :)', '21.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_dislikes`
--
ALTER TABLE `comment_dislikes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connection_requests`
--
ALTER TABLE `connection_requests`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_dislikes`
--
ALTER TABLE `post_dislikes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=344;
--
-- AUTO_INCREMENT for table `comment_dislikes`
--
ALTER TABLE `comment_dislikes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `comment_likes`
--
ALTER TABLE `comment_likes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `connections`
--
ALTER TABLE `connections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `connection_requests`
--
ALTER TABLE `connection_requests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=160;
--
-- AUTO_INCREMENT for table `post_dislikes`
--
ALTER TABLE `post_dislikes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
