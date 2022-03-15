-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2022 at 02:03 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quotesdb`
--
CREATE DATABASE IF NOT EXISTS `quotesdb` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci;
USE `quotesdb`;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `author` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `author`) VALUES
(17, 'Albert Einstein'),
(4, 'Anthony Burgess'),
(18, 'David Mamet'),
(19, 'Diogenes'),
(22, 'Friedrich Nietzsche'),
(16, 'Gene Roddenberry'),
(12, 'H. P. Lovecraft'),
(15, 'Herbert Hoover'),
(20, 'Kurt Cobain'),
(1, 'Laurence J. Peter'),
(21, 'Marie von Ebner-Eschenbach'),
(9, 'Martin Luther King, Jr.'),
(6, 'Maya Angelou'),
(10, 'Niccolo Machiavelli'),
(5, 'Oscar Wilde'),
(8, 'Peter Abelard'),
(2, 'Phyllis Diller'),
(11, 'Plato'),
(14, 'Rosa Parks'),
(7, 'Stephen King'),
(3, 'Steven Wright'),
(13, 'Sun Tzu');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(3, 'Fear'),
(1, 'Fight'),
(4, 'Funny'),
(2, 'Truth'),
(5, 'Youth');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `quote` varchar(200) NOT NULL,
  `authorId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quote`, `authorId`, `categoryId`) VALUES
(1, 'If two wrongs don\'t make a right, try three.', 1, 4),
(4, 'I want my children to have all the things I couldn\'t afford. Then I want to move in with them.', 2, 4),
(5, 'This suspense is terrible. I hope it will last.', 5, 4),
(2, 'I can resist everything except temptation.', 5, 4),
(6, 'What\'s another word for Thesaurus?', 3, 4),
(7, 'Laugh and the world laughs with you, snore and you sleep alone.', 4, 4),
(8, 'Man is least himself when he talks in his own person. Give him a mask, and he will tell you the truth.', 5, 2),
(9, 'I believe that unarmed truth and unconditional love will have the final word in reality. This is why right, temporarily defeated, is stronger than evil triumphant.', 9, 2),
(10, 'By doubting we are led to question, by questioning we arrive at the truth.', 8, 2),
(11, 'Only enemies speak the truth; friends and lovers lie endlessly, caught in the web of duty.', 7, 2),
(12, 'There\'s a world of difference between truth and facts. Facts can obscure the truth.', 6, 2),
(13, 'It is better to be feared than loved, if you cannot be both.', 10, 3),
(14, 'We can easily forgive a child who is afraid of the dark; the real tragedy of life is when men are afraid of the light.', 11, 3),
(15, 'The oldest and strongest emotion of mankind is fear, and the oldest and strongest kind of fear is fear of the unknown.', 12, 3),
(16, 'If you know the enemy and know yourself you need not fear the results of a hundred battles.', 13, 3),
(17, 'I have learned over the years that when one\'s mind is made up, this diminishes fear; knowing what must be done does away with fear.', 14, 3),
(18, 'Never go to bed mad. Stay up and fight.', 2, 1),
(19, 'I am not only a pacifist but a militant pacifist. I am willing to fight for peace. Nothing will end war unless the people themselves refuse to go to war.', 17, 1),
(20, 'The strength of a civilization is not measured by its ability to fight wars, but rather by its ability to prevent them.', 16, 1),
(21, 'He will win who knows when to fight and when not to fight.', 13, 1),
(22, 'Older men declare war. But it is the youth that must fight and die.', 15, 1),
(23, 'The surest way to corrupt a youth is to instruct him to hold in higher esteem those who think alike than those who think differently.', 22, 5),
(24, 'In youth we learn; in age we understand.', 21, 5),
(25, 'The duty of youth is to challenge corruption.', 20, 5),
(26, 'The foundation of every state is the education of its youth.', 19, 5),
(27, 'Old age and treachery will always beat youth and exuberance.', 18, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `author` (`author`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category` (`category`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `authorId` (`authorId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_ibfk_1` FOREIGN KEY (`authorId`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `quotes_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`);


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table authors
--

--
-- Metadata for table categories
--

--
-- Metadata for table quotes
--

--
-- Metadata for database quotesdb
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
