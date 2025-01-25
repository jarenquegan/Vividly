-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 14, 2024 at 06:34 PM
-- Server version: 8.2.0
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vividly`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int NOT NULL,
  `about` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disclaimer` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms_of_use` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `privacy_policy` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `about`, `disclaimer`, `terms_of_use`, `privacy_policy`) VALUES
(1, 'VIVIDLY aims to create a comprehensive and enriching experience for BMMA students, promoting creativity, skill development, and a supportive community within the digital realm.', 'The information provided by VIVIDLY (\"we,\" \"us,\" or \"our\") on vividly.com is for general informational purposes only. All information on the website is provided in good faith; however, we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the site.\r\n\r\nUnder no circumstance shall we have any liability to you for any loss or damage of any kind incurred as a result of the use of the site or reliance on any information provided on the site. Your use of the site and your reliance on any information on the site is solely at your own risk.', '1. Acceptance of Terms:\r\nBy accessing this website, you agree to be bound by these Terms of Use, all applicable laws, and regulations, and agree that you are responsible for compliance with any applicable local laws.\r\n2. Use License:\r\nPermission is granted to temporarily download one copy of the materials on VIVIDLY\'s website for personal, non-commercial transitory viewing only.\r\n3. Disclaimer:\r\nThe materials on VIVIDLY\'s website are provided on an \'as is\' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties.\r\n4. Limitations:\r\nIn no event shall VIVIDLY or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on our website.\r\n5. Revisions and Errata:\r\nThe materials appearing on VIVIDLY\'s website could include technical, typographical, or photographic errors. We do not warrant that any of the materials on its website are accurate, complete, or current.\r\n6. Links:\r\nVIVIDLY has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by us.\r\n7. Modifications:\r\nWe may revise these terms of use for its website at any time without notice. By using this website, you are agreeing to be bound by the then-current version of these Terms of Use.', '1. Information We Collect:\r\nWe may collect personal information, such as names, email addresses, and user-generated content when voluntarily submitted by our users.\r\n2. How We Use Collected Information:\r\nWe may use the information we collect to personalize user experience, improve our website, and send periodic emails.\r\n3. Cookies:\r\nOur website may use \"cookies\" to enhance the user experience. Users may choose to set their web browser to refuse cookies or to alert them when cookies are being sent.\r\n4. Third-Party Disclosure:\r\nWe do not sell, trade, or otherwise transfer to outside parties your personally identifiable information.\r\n5. Security:\r\nWe implement a variety of security measures to maintain the safety of your personal information.\r\nChanges to This Privacy Policy:\r\n6. We reserve the right to update or change our Privacy Policy at any time. Your continued use of the website after any changes will constitute your acknowledgment of the modifications and your consent to abide and be bound by the modified Privacy Policy.\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `artist_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `firstname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middlename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lastname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `suffix` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `emailaddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `artist_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user_default.png',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `acct_type` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'User',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`artist_id`, `username`, `firstname`, `middlename`, `lastname`, `suffix`, `birthdate`, `emailaddress`, `phone_number`, `address`, `bio`, `password`, `artist_pic`, `is_banned`, `acct_type`, `created_at`) VALUES
(1, 'johndoe', 'John', 'Robert', 'Doe', '', '1990-05-15', 'john.doe@example.com', '+631234567890', '123 Main St, Cityville', 'Bio for John Doe', '\'', 'NO_END_CG_2.webp', 0, 'User', '2023-12-19 15:43:48'),
(2, 'jarenquegan', 'Jaren', '', 'Quegan', '', '2001-09-29', 'queganjaren@gmail.com', '+639260876816', 'Carig Sur', 'Hey, Guys! Passionate artist bringing life to canvases. Exploring emotions through colors and creating a world of inspiration. \r\n#PagodNaAko🔪 \r\n#dattebayo', '@L03e1t3', 'IMG_229.jpg', 0, 'Admin', '2023-12-19 16:28:43'),
(3, 'juandelacruz', 'Juan', '', 'Dela Cruz', '', '2000-01-03', 'delacruzjuan@yahoo.com', '+630123456789', '', '', '1234', 'juandelacruz.jpg', 0, 'User', '2023-12-19 16:39:26'),
(5, 'hazelpinera', 'Hazel', 'Baltazar', 'Pinera', '', '2002-04-11', 'pinerahazel@gmail.com', '09260876816', 'Centro Sur, Sto. Nino, Cagayan', '', '\'', 'hazelpinera.jpg', 0, 'Admin', '2023-12-25 12:00:11'),
(11, 'chandlerbing', 'Chandler', '', 'Bing', '', '2001-09-29', 'bingchandler@gmai.com', '09260876816', 'Zone 1, Tabang, Sto. Nino, Cagayan', '', '\'', 'ChandlerBing.webp', 0, 'User', '2023-12-26 10:39:25'),
(12, 'renatoquequegan', 'Renato', '', 'Quequegan', '', '1963-02-16', 'quequeganrenato@gmail.com', '09358417326', 'Zone 1, Tabang, Sto. Nino, Cagayan', '', '\'', 'atoyquegan.jpg', 0, 'User', '2023-12-26 10:42:51'),
(13, 'johnny2xyespapa', 'Johnny', 'Yes', 'Papa', 'Jr. ', '2001-12-02', 'johnnypapa@gmail.com', '+639260876816', 'Carig Sur', 'Nothing to see here.', '1234', '20200816_164556.jpg', 0, 'User', '2023-12-26 14:56:51'),
(14, 'agusterodin', 'François Auguste', 'René', 'Rodin', '', '1840-11-12', 'agusterodin@gmail.com', '', 'Paris, Kingdom of France', 'Nothing is a waste of time if you use the experience wisely.', 'password', 'Auguste-Rodin.jpg', 0, 'User', '2024-01-04 09:24:35'),
(15, 'chuckclose', 'Charles Thomas', '', 'Close', '', '1940-07-05', 'chuckclose@gmail.com', '', 'Monroe, Washington, U.S', 'Art saved my life', 'password', 'Chuck-Close.jpg', 0, 'User', '2024-01-04 09:24:35'),
(16, 'claudemonet', 'Claude Oscar', '', 'Monet', '', '1840-11-14', 'claudemonet@gmail.com', '', 'Giverny, France', 'Color is my day-long obsession, joy and torment.', 'password', 'Claude-Monet.jpg', 0, 'User', '2024-01-04 09:24:35'),
(17, 'pablopicasso', 'Pablo Diego José', 'Picasso', 'Ruiz', '', '1881-10-25', 'pablopicasso@gmail.com', '', 'Málaga, Kingdom of Spain', 'Art washes away from the soul the dust of everyday life.', 'password', 'Pablo-Picasso.jpg', 0, 'User', '2024-01-04 09:24:35'),
(18, 'jacksonpollock', 'Paul', 'Jackson', 'Pollock', '', '1912-01-28', 'jacksonpollock@gmail.com', '', 'Cody, Wyoming, U.S', 'The painting has a life of its own', 'password', 'Jackson-Pollock.jpg', 0, 'User', '2024-01-04 09:24:35'),
(19, 'roylichtenstein', 'Roy Fox', '', 'Lichtenstein', '', '1923-10-27', 'roylichtenstein@gmail.com', '', 'New York City, U.S.', 'I like to pretend that my art has nothing to do with me.', 'password', 'Roy-Lichtenstein.jpg', 0, 'User', '2024-01-04 09:24:35'),
(20, 'sollewitt', 'Solomon', '', 'LeWitt', '', '1928-09-09', 'sollewitt@gmail.com', '', 'Hartford, Connecticut, US', 'A blind man can make art if what is in his mind can be passed to another mind in some tangible form.', 'password', 'Sol-LeWitt.jpg', 0, 'User', '2024-01-04 09:24:35'),
(21, 'gustavklimt', 'Gustav', '', 'Klimt', '', '1862-07-14', 'gustavklimt@gmail.com', '', 'Baumgarten, Vienna, Austria', 'I have never painted a self-portrait. I am less interested in myself as a subject for painting than I am in other people, above all women.', 'password', 'Gustav-Klimt.jpg', 0, 'User', '2024-01-04 09:24:35'),
(22, 'leonardodavinci', 'Leonardo', '', '', '', '1452-04-15', 'leonardodavinci@gmail.com', '', 'Vinci, Republic of Florence', 'Art is never finished, only abandoned.', 'password', 'Leonardo-da-Vinci.jpg', 0, 'User', '2024-01-04 09:24:35'),
(23, 'vladimirtatlin', 'Vladimir', 'Yevgrafovic', 'Tatlin', '', '1885-12-16', 'vladimirtatlin@gmail.com', '', 'Ukraine', 'The influence of my art is expressed in the movement of the Constructivists, of which I am the founder.', 'password', 'Vladimir-Tatlin.jpg', 0, 'User', '2024-01-04 09:24:35'),
(24, 'karawalker', 'Kara Elizabeth', '', 'Walker', '', '1969-11-26', 'karawalker@gmail.com', '', 'Stockton, California, U.S.', 'I have no interest in making a work that doesn\'t elicit a feeling.', 'password', 'Kara-Walker.jpg', 0, 'User', '2024-01-04 09:24:35'),
(25, 'gyulakosice', 'Ferdinand', '', 'Fallik', '', '1924-04-26', 'gyulakosice@gmail.com', '', 'Košice, Czechoslovakia', 'As I see it, a painting is conceived as a totality that begins and ends in itself.', 'password', 'Gyula-Kosice.jpg', 0, 'User', '2024-01-04 09:24:35'),
(26, 'mcescher', 'Maurits Cornelis ', '', 'Escher', '', '1898-06-17', 'mauritsescher@gmail.com', '', 'Leeuwarden, Netherlands', 'My work is a game, a very serious game.', 'password', 'Maurits-Cornelis-Escher.jpg', 0, 'User', '2024-01-04 09:24:35'),
(27, 'markryden', 'Mark', '', 'Ryden', '', '1963-01-20', 'markryden@gmail.com', '', 'Medford, Oregon, U.S.', 'I find it so much easier to be creatively free at night. Daytime is for sleeping. Nighttime is the best time for making art. The later at night it gets the further into another world you go.', 'password', 'Mark-Ryden.jpg', 0, 'User', '2024-01-04 09:24:35'),
(28, 'andreamantegna', 'Andrea', '', '', '', '1431-01-01', 'andreamantegna@gmail.com', '', 'Isola Mantegna, Italy', 'The artist\'s duty is to illuminate the human experience, blending classical wisdom with the boundless possibilities of creativity. Through skillful craftsmanship and an unwavering dedication to one\'s art, the painter can elevate the viewer\'s understanding of beauty and truth.', 'password', 'Andrea-Mantegna.jpg', 0, 'User', '2024-01-04 09:24:35'),
(29, 'jeanbasquiat', 'Jean Michel', '', 'Basquiat', '', '1960-12-22', 'jeanbasquiat@gmail.com', '', 'New York City, U.S.', 'I don\'t think about art when I\'m working. I try to think about life.', 'password', 'Basquiat-Jean-Michel.jpg', 0, 'User', '2024-01-04 09:24:35'),
(30, 'marcelduchamp', 'Henri Robert Marcel', '', 'Duchamp', '', '1887-07-28', 'marcelduchamp@gmail.com', '', 'Blainville-Crevon, France', 'I don\'t believe in art. I believe in artists.', 'password', 'Marcel-Duchamp.jpg', 0, 'User', '2024-01-04 09:24:35'),
(31, 'henrydarger', 'Henry Joseph', '', 'Darger', 'Jr.', '1892-04-12', 'henrydarger@gmail.com', '', 'Chicago, Illinois, U.S.', 'In the vast realms of the unreal, where imagination meets the poignant realities of life, art becomes a refuge—a sanctuary where fantastical narratives unfold, giving voice to the hidden stories that dwell within the depths of the human soul.', 'password', 'Henry-Darger.jpg', 0, 'User', '2024-01-04 09:24:35'),
(32, 'ducciodibuoninsegna', 'Duccio', '', '', '', '1255-01-01', 'duccio@gmail.com', '', 'Siena, Republic of Siena', 'In each stroke, I sought to weave a tapestry of reverence, where colors spoke the language of the divine, and the canvas echoed the quiet beauty that transcends time.', 'password', 'Duccio-di-Buoninsegna.jpg', 0, 'User', '2024-01-04 09:24:35'),
(33, 'henribresson', 'Henri', 'Cartier', 'Bresson', '', '1908-08-22', 'henribresson@gmail.com', '', 'Chanteloup-en-Brie, France', 'To photograph is to put on the same line of sight the head, the eye, and the heart. It\'s a way of life.', 'password', 'Henri-Cartier-Bresson.jpg', 0, 'User', '2024-01-04 09:24:35'),
(34, 'beeple', 'Michael Joseph', '', 'Winkelmann', '', '1981-06-20', 'beeple@gmail.com', '', 'Fond du Lac, Wisconsin, U.S.', 'I believe in the power of digital art to redefine creativity and ownership. NFTs provide a groundbreaking way for artists to share and monetize their work in the digital realm, creating new possibilities for the art world.', 'password', 'Beeple.jpg', 0, 'User', '2024-01-04 09:24:35'),
(35, 'diegovelazquez', 'Diego', 'Velázquez', 'Rodríguez de Silva', '', '1599-06-06', 'diegovelazquez@gmail.com', '', 'Seville, Spain', 'I would rather be the first painter of common things than second in the higher art.', 'password', 'Diego-Velázquez.jpg', 0, 'User', '2024-01-04 09:24:35'),
(36, 'georgesseurat', 'Georges', 'Pierre', 'Seurat', '', '1859-12-02', 'georgesseurat@gmail.com', '', 'Paris, France', 'Some say they see poetry in my paintings; I see only science\r\nFrench Post-Impressionist Artist', 'password', 'Georges-Seurat.jpg', 0, 'User', '2024-01-04 09:24:35'),
(37, 'banksy', '', '', '', '', '1974-07-28', 'banksy@gmail.com', '', 'Bristol, England', 'Art should comfort the disturbed and disturb the comfortable.\r\nStreet Artist', 'password', 'Banksy.jpg', 0, 'User', '2024-01-04 09:24:35'),
(38, 'henrimatisse', 'Henri Émile', 'Benoît', 'Matisse', '', '1869-12-31', 'henrimatisse@gmail.com', '', 'Le Cateau-Cambrésis, France', 'I don’t paint things. I only paint the difference between things\r\nFrench Visual Artist', 'password', 'Henri-Matisse.jpg', 0, 'User', '2024-01-04 09:24:35'),
(39, 'examplevividly', 'No', '', '', '', NULL, 'examplevividly@gmail.com', '', '', '', '\'', 'user_default.png', 0, 'User', '2024-01-11 02:45:51'),
(40, 'gab', 'Gabriela ', '', 'Mallillin', '', '2024-01-04', 'gab@gmail.com', '09613167455', 'Annafunan sa puso mo', '', 'g', 'WIN_20231029_21_23_07_Pro.jpg', 0, 'User', '2024-01-12 08:28:43'),
(41, 'keant', 'Stephen Keant', '', 'Castaneda', '', '2002-08-31', 'skeant31@gmail.com', '09263914314', 'Jaan lang sa tabi', '\"Life is what happens when you\'re busy making other plans.\" — John Lennon\r\n\r\n', '123456', '417778193_394877139595351_8735348928218592152_n.jpg', 0, 'Admin', '2024-01-14 05:46:46'),
(42, 'Jephunneh', 'Jephunneh Jediael', 'Almazan', 'Tugas', '', '2003-05-11', 'jephtugas11@gmail.com', '', 'Lasam, Cagayan', '', 'Hennuhpejsagut1?', 'Jephunneh.jpg', 0, 'Admin', '2024-01-14 05:48:55'),
(43, 'davinci', 'da', '', 'Vin ci', 'Biot', '2003-06-29', 'navarretterovin@gmail.com', '09280336397', 'Antonio St. Carig Sur , Tuguegarao CIty', 'Crazy ', '@projectproposal101', 'user_default.png', 0, 'Admin', '2024-01-14 05:56:02');

-- --------------------------------------------------------

--
-- Table structure for table `artists_artworks`
--

CREATE TABLE `artists_artworks` (
  `artist_id` int NOT NULL,
  `artwork_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists_artworks`
--

INSERT INTO `artists_artworks` (`artist_id`, `artwork_id`, `created_at`) VALUES
(1, 1, '2023-12-19 15:45:22'),
(2, 4, '2023-12-20 06:11:37'),
(2, 5, '2023-12-20 06:11:37'),
(2, 6, '2023-12-20 05:21:42'),
(2, 7, '2023-12-22 01:50:50'),
(2, 17, '2023-12-26 07:33:18'),
(2, 19, '2023-12-26 15:06:26'),
(2, 70, '2024-01-05 04:20:27'),
(3, 2, '2023-12-22 09:17:40'),
(3, 13, '2023-12-25 03:46:36'),
(14, 20, '2024-01-04 10:00:49'),
(14, 21, '2024-01-04 10:00:49'),
(15, 24, '2024-01-04 10:00:49'),
(15, 67, '2024-01-04 10:00:49'),
(16, 25, '2024-01-04 10:00:49'),
(16, 26, '2024-01-04 10:00:49'),
(17, 27, '2024-01-04 10:00:49'),
(17, 68, '2024-01-04 10:00:49'),
(18, 28, '2024-01-04 10:00:49'),
(18, 69, '2024-01-04 10:00:49'),
(19, 29, '2024-01-04 10:00:49'),
(19, 30, '2024-01-04 10:00:49'),
(20, 37, '2024-01-04 10:00:49'),
(20, 38, '2024-01-04 10:00:49'),
(21, 39, '2024-01-04 10:00:49'),
(21, 40, '2024-01-04 10:00:49'),
(22, 41, '2024-01-04 10:00:49'),
(22, 42, '2024-01-04 10:00:49'),
(23, 43, '2024-01-04 10:00:49'),
(23, 44, '2024-01-04 10:00:49'),
(24, 45, '2024-01-04 10:00:49'),
(24, 46, '2024-01-04 10:00:49'),
(25, 47, '2024-01-04 10:00:49'),
(25, 48, '2024-01-04 10:00:49'),
(26, 49, '2024-01-04 10:00:49'),
(26, 50, '2024-01-04 10:00:49'),
(27, 51, '2024-01-04 10:00:49'),
(27, 52, '2024-01-04 10:00:49'),
(28, 53, '2024-01-04 10:00:49'),
(28, 54, '2024-01-04 10:00:49'),
(29, 55, '2024-01-04 10:00:49'),
(29, 56, '2024-01-04 10:00:49'),
(30, 57, '2024-01-04 10:00:49'),
(30, 58, '2024-01-04 10:00:49'),
(31, 59, '2024-01-04 10:00:49'),
(31, 60, '2024-01-04 10:00:49'),
(32, 61, '2024-01-04 10:00:49'),
(32, 62, '2024-01-04 10:00:49'),
(33, 63, '2024-01-04 10:00:49'),
(33, 64, '2024-01-04 10:00:49'),
(34, 65, '2024-01-04 10:00:49'),
(34, 66, '2024-01-04 10:00:49'),
(35, 22, '2024-01-04 10:00:49'),
(35, 23, '2024-01-04 10:00:49'),
(36, 31, '2024-01-04 10:00:49'),
(36, 32, '2024-01-04 10:00:49'),
(37, 33, '2024-01-04 10:00:49'),
(37, 34, '2024-01-04 10:00:49'),
(38, 35, '2024-01-04 10:00:49'),
(38, 36, '2024-01-04 10:00:49'),
(41, 76, '2024-01-14 05:54:26'),
(42, 78, '2024-01-14 06:09:42'),
(42, 79, '2024-01-14 06:15:12'),
(43, 77, '2024-01-14 06:00:58');

-- --------------------------------------------------------

--
-- Table structure for table `artist_followers`
--

CREATE TABLE `artist_followers` (
  `follower_id` int NOT NULL,
  `artist_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artist_followers`
--

INSERT INTO `artist_followers` (`follower_id`, `artist_id`, `created_at`) VALUES
(1, 2, '2023-12-22 19:33:30'),
(2, 1, '2024-01-05 04:12:01'),
(2, 3, '2023-12-24 00:12:15'),
(2, 5, '2023-12-25 14:38:05'),
(2, 37, '2024-01-12 03:14:35'),
(2, 40, '2024-01-12 08:40:13'),
(2, 41, '2024-01-14 06:09:23'),
(2, 42, '2024-01-14 07:22:31'),
(2, 43, '2024-01-14 07:17:51'),
(3, 11, '2023-12-26 11:53:23'),
(5, 2, '2023-12-26 15:13:38'),
(11, 2, '2023-12-26 15:15:04'),
(13, 2, '2023-12-26 15:12:13'),
(14, 2, '2024-01-12 03:10:52'),
(17, 2, '2024-01-12 03:12:42'),
(18, 2, '2024-01-12 03:13:10'),
(39, 2, '2024-01-11 02:48:16'),
(40, 2, '2024-01-12 08:32:24'),
(41, 2, '2024-01-14 08:30:07'),
(43, 2, '2024-01-14 05:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `artist_liked_artworks`
--

CREATE TABLE `artist_liked_artworks` (
  `id` int NOT NULL,
  `artist_id` int DEFAULT NULL,
  `artwork_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artist_liked_artworks`
--

INSERT INTO `artist_liked_artworks` (`id`, `artist_id`, `artwork_id`, `created_at`) VALUES
(24, 1, 2, '2023-12-20 14:46:33'),
(27, 1, 5, '2023-12-20 14:46:53'),
(72, 1, 6, '2023-12-20 15:08:20'),
(948, 2, 6, '2023-12-22 00:11:10'),
(953, 3, 6, '2023-12-22 00:12:49'),
(959, 3, 4, '2023-12-22 00:21:02'),
(988, 2, 5, '2023-12-22 08:15:15'),
(990, 1, 7, '2023-12-22 11:14:40'),
(1032, 1, 4, '2023-12-23 02:32:38'),
(1041, 2, 7, '2023-12-23 08:06:08'),
(1044, 3, 5, '2023-12-23 08:10:07'),
(1045, 3, 7, '2023-12-23 14:27:11'),
(1047, 2, 4, '2023-12-25 09:46:44'),
(1053, 3, 17, '2023-12-26 11:53:35'),
(1054, 2, 1, '2023-12-26 12:17:31'),
(1056, 13, 19, '2023-12-26 15:11:06'),
(1057, 13, 4, '2023-12-26 15:12:23'),
(1058, 5, 19, '2023-12-26 15:13:44'),
(1059, 11, 19, '2023-12-26 15:14:35'),
(1060, 1, 19, '2023-12-26 15:15:47'),
(1061, 3, 19, '2023-12-26 15:16:34'),
(1062, 2, 60, '2024-01-04 13:14:19'),
(1063, 2, 19, '2024-01-04 18:51:47'),
(1064, 2, 45, '2024-01-04 19:13:54'),
(1068, 1, 40, '2024-01-05 04:08:26'),
(1069, 1, 68, '2024-01-05 04:08:49'),
(1071, 2, 30, '2024-01-05 04:54:40'),
(1072, 2, 70, '2024-01-05 04:56:57'),
(1073, 1, 1, '2024-01-05 05:49:03'),
(1074, 1, 70, '2024-01-05 05:49:55'),
(1075, 39, 70, '2024-01-11 02:47:58'),
(1076, 39, 19, '2024-01-11 02:48:10'),
(1077, 39, 4, '2024-01-11 02:48:39'),
(1078, 14, 70, '2024-01-12 03:10:56'),
(1079, 15, 70, '2024-01-12 03:11:29'),
(1080, 16, 70, '2024-01-12 03:11:57'),
(1081, 17, 70, '2024-01-12 03:12:39'),
(1082, 18, 70, '2024-01-12 03:13:06'),
(1083, 2, 13, '2024-01-12 04:08:58'),
(1084, 40, 70, '2024-01-12 08:32:38'),
(1085, 40, 4, '2024-01-12 08:35:05'),
(1086, 42, 77, '2024-01-14 06:01:41'),
(1088, 41, 77, '2024-01-14 06:03:14'),
(1089, 2, 76, '2024-01-14 06:05:19'),
(1090, 2, 78, '2024-01-14 06:13:39'),
(1091, 42, 64, '2024-01-14 06:19:32'),
(1092, 2, 79, '2024-01-14 07:16:52'),
(1093, 2, 77, '2024-01-14 07:21:49'),
(1094, 41, 70, '2024-01-14 08:29:59'),
(1095, 41, 4, '2024-01-14 08:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `artworks`
--

CREATE TABLE `artworks` (
  `artwork_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `style` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artworks`
--

INSERT INTO `artworks` (`artwork_id`, `title`, `description`, `style`, `created_at`, `image_url`) VALUES
(1, 'Photography', 'Description of the artwork', 'Abstract', '2023-12-19 15:44:59', 'IMG_229.jpg'),
(2, 'Steph Vs. LeBron', 'Steph defeated Lebron', 'Photoshop', '2023-12-19 16:32:21', 'IMG_0499.jpeg'),
(4, 'Nature Keep Calm', 'Immersed in the vibrant palette of creativity, I am Jaren G. Quegan, a visionary artist weaving dreams into the canvas of reality. With each stroke, I dance between the realms of imagination and emotion, crafting a symphony of colors that speaks to the soul.\r\n\r\nMy artistic journey is a kaleidoscope of inspiration drawn from the beauty of nature, the complexity of human emotions, and the endless possibilities of the cosmos. As an alchemist of aesthetics, I breathe life into my creations, inviting viewers to embark on a visual odyssey.\r\n\r\nExploring various mediums, from the bold strokes of acrylic to the delicate intricacies of digital art, my portfolio is a testament to the boundless horizons of artistic expression. Through my work, I aim to spark curiosity, ignite passion, and transcend the ordinary, leaving an indelible mark on the tapestry of artistic discourse.\r\n\r\nBeyond the canvas, I find inspiration in the interplay of light and shadow, the rhythmic melodies of life, and the nuanced stories woven into the fabric of culture. With an insatiable thirst for innovation, I continually push the boundaries of my craft, seeking new avenues to articulate the ineffable.\r\nJoin me on this artistic odyssey, where every creation is a chapter in the story of my soul. Through the strokes of my brush or the pixels on the screen, I strive to create a harmonious dialogue between art and observer—a dialogue that transcends words and resonates in the hearts of those who dare to dream with their eyes wide open.', 'Oil on Canvas', '2023-12-19 16:36:34', 'beautiful-nature-landscape-with-river-vegetation.jpg'),
(5, 'Power', 'Okay lagyan ng description', 'Painting, Realism', '2023-12-20 03:06:02', 'pawel-czerwinski-kNx8H8ScHDk-unsplash.jpg'),
(6, 'Road to Forever', 'Dito ka lang sa piling ko. Punta tayo sa biyaheng walang hanggan.', 'Photograph, Painting', '2023-12-20 05:21:06', '1694588437589.jpg'),
(7, 'Whattanice!', '', 'Photograph', '2023-12-22 01:49:16', 'mar-bustos-ARVFsI-32Uk-unsplash.jpg'),
(13, 'Digi-Car', 'A digital art of a beautiful car! Libre lait. Hehehe', 'Digital Art', '2023-12-25 03:46:36', 'IMG_0436.PNG'),
(17, 'Walang Title', 'Walang Title', 'Painting, Realism', '2023-12-26 07:33:18', '1694588437589.jpg'),
(19, 'Miles Morales in SITSV', 'MIles Morales taking a leap of faith. 🤘🏻', 'Digital Art', '2023-12-26 15:06:26', 'miles-morales-in-spider-man-into-the-spider-verse_3840x2160_xtrafondos.com.jpg'),
(20, 'Le Penseur', '(The Thinker) Nude male figure of heroic size sitting on a rock', 'Realism', '2024-01-04 10:00:49', 'Rodin-the-thinker.jpg'),
(21, 'La Porte de l\'Enfer', '(The Gates of Hell) Depicts a scene from the Inferno, the first section of Dante Alighieri\'s Divine Comedy', 'Impressionism, Modern Art', '2024-01-04 10:00:49', 'Rodin-the-gates-of-hell.jpg'),
(22, 'The Maids of Honour', '(Las Meninas) Composed of a strange cast of characters, including a princess, a nun, a dwarf and the Baroque artist himself', 'Baroque', '2024-01-04 10:00:49', 'Velázquez-las-meninas.jpg'),
(23, 'Retrato del Papa Inocencio X', '(Portrait of Pope Innocent X) An unflinching portrait of a highly intelligent, shrewd, and aging man', 'Baroque Realism', '2024-01-04 10:00:49', 'Velázquez-portrait-of-pope-innocent-x.jpg'),
(24, 'Big Self-Portrait', 'A massive 9ft high painting of a head around fifty times life-size', 'Photorealism', '2024-01-04 10:00:49', 'Close-big-self-portrait.jpg'),
(25, 'Impression, Sunrise', 'Depicts the port of Le Havre, Monet\'s hometown', 'Impressionism', '2024-01-04 10:00:49', 'Monet-impression-sunrise.jpg'),
(26, 'La Japonaise', 'Monet depicts Camille in a padded, heavily decorated red kimono (an uchikake) belonging to a famous Japanese actor', 'Impressionism', '2024-01-04 10:00:49', 'Monet-la-japonaise.jpg'),
(27, 'Les Demoiselles d\'Avignon', 'Portrays five nude female prostitutes in a brothel on Carrer d\'Avinyó, a street in Barcelona, Spain', 'Cubism', '2024-01-04 10:00:49', 'Picasso-les-demoiselles-d_Avignon.jpg'),
(28, 'No. 5, 1948', 'A bird-nest-like depiction of chaos and a prime example of abstract expressionism', 'Abstract Expressionism', '2024-01-04 10:00:49', 'Pollock-no.5,1948.jpg'),
(29, 'Crying Girl', 'A style derived from comic strips, portray the trivialization of culture endemic in contemporary American life', 'Pop Art', '2024-01-04 10:00:49', 'Lichtenstein-cying-girl.png'),
(30, 'Look Mickey', 'Look Mickey represents the first time Roy Lichtenstein directly transposed a scene and a style from a source of popular culture', 'Pop Art', '2024-01-04 10:00:49', 'Lichtenstein-look-mickey.jpg'),
(31, 'A Sunday On La Grande Jatte', 'Revealing the essence of modern existence and its double-edged sword of social spectacle and isolation', 'Pointilism', '2024-01-04 10:00:49', 'Seurat-a-sunday-afternoon-on-la-grande.jpg'),
(32, 'Vase of Flowers', 'The vase is off-white color with the suggestion of a something green painted on it', 'Post-Impressionism, Modern Art', '2024-01-04 10:00:49', 'Seurat-vase-of-flowers.jpg'),
(33, 'Flower Thrower', 'A masked man throwing a bunch of flowers', 'Street Art', '2024-01-04 10:00:49', 'Banksy-flower-thrower.png'),
(34, 'Girl with Balloon', 'A young girl with her hand extended toward a red heart-shaped balloon carried away by the wind', 'Street Art', '2024-01-04 10:00:49', 'Banksy-girl-with-baloon.jpeg'),
(35, 'Woman with a Hat', 'Depicts Matisse\'s wife, Amelie', 'Fauvism', '2024-01-04 10:00:49', 'Matisse-woman-with-a-hat.jpg'),
(36, 'Le bonheur de vivre', 'Nude women and men cavort, play music, and dance in a landscape drenched with vivid color', 'Fauvism, Modernism', '2024-01-04 10:00:49', 'Matisse-le-bonheur-de-vivre.jpg'),
(37, 'Wall Drawing #1136', 'A colourful and lively acrylic paint installation', 'Minimalism', '2024-01-04 10:00:49', 'LeWitt-wall-drawing-1136.jpg'),
(38, 'Wall Drawing #122', 'All combinations of two lines crossing, placed at random, using arcs from corners and sides, straight, not straight and broken lines', 'Minimalism', '2024-01-04 10:00:49', 'LeWitt-wall-drawing-122.jpg'),
(39, 'Death and Life', 'An allegorical Grim Reaper who gazes at “life” with a malicious grin.', 'Art Nouveau, Symbolism', '2024-01-04 10:00:49', 'Klimt-death-and-life.jpg'),
(40, 'The Kiss', 'A couple embracing each other, their bodies entwined in elaborate beautiful robes decorated in a style', 'Art Nouveau, Modern art, Symbolism, Vienna Secession', '2024-01-04 10:00:49', 'Klimt-the-kiss.jpg'),
(41, 'Mona Lisa', 'The woman sits markedly upright in a \"pozzetto\" armchair with her arms folded, a sign of her reserved posture.', 'Cinquecento (16th-century Italian Renaissance)', '2024-01-04 10:00:49', 'Leonardo-da-Vinci-mona-lisa.jpg'),
(42, 'Virgin of the Rocks', 'The painting shows the Virgin Mary with Saint John the Baptist, Christ\'s cousin, and an angel.', 'Renaissance, High Renaissance', '2024-01-04 10:00:49', 'Leonardo-da-Vinci-virgin-of-the-rocks.jpg'),
(43, 'Letatlin', 'A conceptual human-powered ornithopter created in 1929—1932 by Vladimir Tatlin.', 'Constructivism', '2024-01-04 10:00:49', 'Tatlin-letatlin.jpg'),
(44, 'Tatlinʼs Tower', '(Model of the monument III International) A design for a grand monumental building by the Russian artist and architect Vladimir Tatlin, that was never built.', 'Constructivism', '2024-01-04 10:00:49', 'Tatlin-model-of-the-monument-III-international.jpg'),
(45, 'Darkytown Rebellion', 'A large-scale mixed media installation composed of seventeen cut paper silhouettes, a framed landscape painting and wall projection.', 'Contemporary Art, Conceptual Art', '2024-01-04 10:00:49', 'Walker-darkytown-rebellion.jpg'),
(46, 'Subtlety', 'A white sculpture depicting a woman with African features in the shape of a sphinx\r\n', 'Contemporary art, Conceptual art', '2024-01-04 10:00:49', 'Walker-subtlety.jpg'),
(47, 'La ciudad hidroespacial', '(The Hydrospacial City) A utopian vision for architecture in space-the Argentinean artist\'s most ambitious and longest-running project.', 'Kinetic art, Contemporary art', '2024-01-04 10:00:49', 'Kosice-la-ciudad-hidroespacial.jpg'),
(48, 'Röyi', 'Created with eight geometric wooden pieces, and mostly with tube-shaped elements, it can be handled to adopt different positions and compositions, and also explores ludic experiences and invites viewers to interact.', 'Kinetic art, Contemporary art', '2024-01-04 10:00:49', 'Kosice-röyi.jpg'),
(49, 'Sky and Water I', 'A black and white woodcut featuring a rhombus or diamond of white fish and black birds with the birds forming the top half of the diamond and the fish the lower half.', 'Op Art', '2024-01-04 10:00:49', 'Escher-sky-and-water-I.jpg'),
(50, 'Three Worlds', 'Depicts a large pool or lake during the autumn or winter months, the title referring to the three visible perspectives in the picture: the surface of the water on which leaves float, the world above the surface, observable by the water\'s reflection of a forest, and the world below the surface, observable in the large fish swimming just below the water\'s surface.', 'Op Art', '2024-01-04 10:00:49', 'Escher-three-worlds.jpg'),
(51, 'The Creatrix', 'A sovereign feminine archetype who creates (and MUST create) her unique soul-attuned legacy and queendom in alignment with her highest soul development and expression without compromise.', 'Lowbrow Art, Pop Surrealism', '2024-01-04 10:00:49', 'Ryden-the-creatrix.jpg'),
(52, 'The Magic Circus', 'The Magic Circus is an eye popping juxtaposition of cartoonlike hybrid animal/toy characters, science book illustrations, and delicate vulnerable children. ', 'Lowbrow Art, Pop Surrealism', '2024-01-04 10:00:49', 'Ryden-the-magic-circus.jpg'),
(53, 'Camera degli Sposi', 'The Camera degli Sposi, sometimes known as the Camera Picta, is a room frescoed with illusionistic paintings by Andrea Mantegna in the Ducal Palace, Mantua, Italy. ', 'Trompe-l\'oeil, Di sotto in sù', '2024-01-04 10:00:49', 'Mantegna-camera-degli-sposi.jpeg'),
(54, 'Ceiling Oculus', 'Mantegna\'s playful ceiling presents an oculus that fictively opens into a blue sky, with foreshortened putti playfully frolicking around a balustrade painted in di sotto in sù to seem as if they occupy real space on the roof above. (Cealing of the Camera Picta)', 'Quadratura, Trompe-l\'œil, Early Renaissance', '2024-01-04 10:00:49', 'Mantegna-ceiling-of-the-camera-picta.jpg'),
(55, 'Riding with Death', 'Depicts two central figures, namely a brown human figure, described as being African American, sitting on top of a white skeletal figure who is on all fours.', 'Neo-expressionism', '2024-01-04 10:00:49', 'Basquiat-riding-with-death.jpg'),
(56, 'Eyes and Eggs', 'The painting depicts a black man in simple clothes and with a frying pan in his right hand, with eggs cooking in the centre.', 'Neo-expressionism', '2024-01-04 10:00:49', 'Basquiat-eyes-and-eggs.jpg'),
(57, 'Fountain ', 'Fountain is a readymade sculpture by Marcel Duchamp in 1917, consisting of a porcelain urinal signed \"R. Mutt\".', 'Site-specific art, Conseptual art', '2024-01-04 10:00:49', 'Duchamp-fountain.jpg'),
(58, 'Bicycle Wheel', 'A readymade from Marcel Duchamp consisting of a bicycle fork with front wheel mounted upside-down on a wooden stool.', 'Site-specific art, Conceptual art', '2024-01-04 10:00:49', 'Duchamp-bicycle-wheel.jpg'),
(59, 'In the Realms of the Unreal', '\"Realms of the Unreal\" is a term often associated with Darger\'s entire body of work, encapsulating the vast, imaginary universe he created in his manuscript. The title reflects the expansive and fantastical nature of the narrative, incorporating elements of warfare, rebellion, and the mystical realms where the Vivian Girls navigate complex challenges.', 'Art Brut (Outsider Art)', '2024-01-04 10:00:49', 'Darger-realms-of-the-unreal.jpg'),
(60, 'The Glandelinians', 'Within Darger\'s narrative of the Glandeco-Angelinian War Storm, this illustration depicts a pivotal moment where the Glandelinians launch an attack on the Christian Nation.', 'Art Brut (Outsider Art)', '2024-01-04 10:00:49', 'Darger-glandelinians-attack-christian-nation.jpg'),
(61, 'Maestà of Duccio', '(The Maestà) The virgin seated on a throne and surrounded by angels and saints, a work destined for the high altar of Siena Cathedral.', 'Gothic Art', '2024-01-04 10:00:49', 'Duccio-maestà.jpg'),
(62, 'The Raising of Lazarus', 'The Raising of Lazarus is a dramatic portrayal of the biblical narrative from the Gospel of John (John 11:38–44). The painting captures the moment when Jesus, standing at the entrance of the tomb, raises Lazarus from the dead.', 'Gothic Art', '2024-01-04 10:00:49', 'Duccio-the-raising-of-lazarus.jpg'),
(63, 'Behind the Gare Saint-Lazare', 'A man leaps over a puddle behind the Gare Saint-Lazare train station in Paris. This image is often cited as one of Cartier-Bresson\'s defining moments.', 'Street Photography', '2024-01-04 10:00:49', 'Bresson-behind-the-gare-saint-lazare.jpg'),
(64, 'Seville', 'Cartier-Bresson captured this photograph in Seville, Spain, showcasing a child running through the streets with wine bottles.', 'Street Photography', '2024-01-04 10:00:49', 'Bresson-seville.jpeg'),
(65, 'Crossroads', 'Crossroads is a digital artwork that was sold as an NFT. It features a dynamic and surreal scene with futuristic elements, reflecting Beeple\'s distinctive style and imaginative vision.', 'Digital Art', '2024-01-04 10:00:49', 'Beeple-crossroads.jpg'),
(66, 'Human One', 'Human One is a digital artwork that showcases Beeple\'s ability to create visually striking and thought-provoking imagery. The piece often features a humanoid figure and intricate details, inviting viewers to explore its layers of complexity.', 'Digital Art', '2024-01-04 10:00:49', 'Beeple-human-one.jpg'),
(67, 'Fanny/Fingerpainting', 'A portrait of Close\'s grandmother-in-law', 'Fingerpainting', '2024-01-04 10:00:49', 'Close-fanny-fingerpainting.jpg'),
(68, 'Head in Blue Background', 'Destitute figures in various states of extremity, resignation and despair', 'Surrealism', '2024-01-04 10:00:49', 'Picasso-head-in-blue-background.jpg'),
(69, 'Moon Woman', 'The artwork represents periodic creation and death, reflecting Pollock\'s Jungian interests', 'Abstract Expressionism', '2024-01-04 10:00:49', 'Pollock-moon-woman.jpg'),
(70, 'Miles Morales', 'Miles Morales', 'Digital Art', '2024-01-05 04:20:27', 'wp12369608-miles-morales-8k-wallpapers.jpg'),
(72, 'Exhausted Developer', 'This is me, I\'m exhausted. Kenchana I am fine. Tengnengneng teng neng.', 'Digital Art', '2024-01-06 16:24:51', 'wallpaperflare.com_wallpaper-5.jpg'),
(73, 'Tokyo Night', 'No description', 'Photograph', '2024-01-07 12:17:31', '19066.jpg'),
(74, 'City night', 'No Description', 'Photograph', '2024-01-07 12:27:38', 'wallpaperflare.com_wallpaper-3.jpg'),
(75, 'Sunset', 'Sunset', 'Photograph', '2024-01-07 12:32:28', 'wallpaperflare.com_wallpaper.jpg'),
(76, 'Spiderman', 'Sapot ni gagamboy', 'DigitalArt,Adobe Photoshop', '2024-01-14 05:54:26', 'spiderman-on-building-p4ashmgeamn2mvkn.jpg'),
(77, 'Testing 101', 'This is to test that i can post in this website', 'Digital Art', '2024-01-14 06:00:58', 'FInal_FBLOGO.png'),
(78, 'Kyot', '', 'Doggo', '2024-01-14 06:09:42', '86ee515d5ef41147cdb58ae8e2ccd1a4fd8984401e152092d5ece3e5f63f8388.jpg'),
(79, 'Meme', '', '', '2024-01-14 06:15:12', '636f356fea784c17e87b5a12_720_664_71181.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `branding`
--

CREATE TABLE `branding` (
  `brand_id` int NOT NULL,
  `brand_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_info` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_logo` varchar(2128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branding`
--

INSERT INTO `branding` (`brand_id`, `brand_name`, `brand_info`, `brand_logo`) VALUES
(1, 'VIVIDLY', 'Vividly is your virtual gallery space, where artists can showcase their creations with vibrant detail. Upload your artwork effortlessly and immerse visitors in a visually stunning journey.\r\n\r\nQuegan, Jaren G.\r\n\r\nCastañeda, Stephen Keant N.\r\nNavarette, Rovin B.\r\nTugas, Jephunneh Jediael A.', 'vividly_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int NOT NULL,
  `artist_id` int DEFAULT NULL,
  `artwork_id` int DEFAULT NULL,
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `artist_id`, `artwork_id`, `comment_text`, `created_at`) VALUES
(109, 2, 4, 'hi sa iyo\r\n', '2023-12-23 11:40:07'),
(110, 2, 4, 'hello', '2023-12-23 11:40:18'),
(111, 3, 4, 'Fogifif', '2023-12-23 11:40:31'),
(112, 3, 4, 'Hello, Jaren, this is a comment test. ', '2023-12-23 11:51:30'),
(113, 3, 1, 'Jaren, you\'re so pogi!! ', '2023-12-23 13:40:31'),
(114, 3, 1, 'Comment without S', '2023-12-23 13:41:04'),
(115, 3, 1, 'Hello, Jaren!! ', '2023-12-23 13:49:43'),
(116, 3, 1, 'Check blue', '2023-12-23 13:58:49'),
(117, 3, 1, 'Hellooo!! ', '2023-12-23 14:19:24'),
(118, 3, 7, 'Ang gandaaa!! ', '2023-12-23 14:27:18'),
(119, 2, 4, 'Hello, comment test 1.', '2023-12-24 00:11:04'),
(120, 1, 6, 'Wow, that road is very nice!! 👍🏻', '2023-12-24 03:56:14'),
(121, 1, 6, 'Just wowww!! ✨', '2023-12-24 03:56:39'),
(124, 2, 6, 'L', '2023-12-24 10:20:13'),
(125, 2, 6, 'The quick brown fox jumps over the head of the lazy dog.', '2023-12-24 10:20:47'),
(126, 1, 5, 'Ganda talaga ng gawa mo, Jaren! Functional pa!', '2023-12-24 13:12:27'),
(127, 2, 4, 'Hello', '2023-12-25 09:54:26'),
(128, 2, 4, 'Heloooo', '2023-12-25 09:54:41'),
(129, 13, 19, 'Wow!! Ang galinggg!!', '2023-12-26 15:11:24'),
(130, 13, 4, 'Ganda netoo!! ', '2023-12-26 15:12:39'),
(131, 11, 19, 'Beautiful!! ', '2023-12-26 15:14:43'),
(132, 1, 19, 'Gandaa!! Galingg!!', '2023-12-26 15:16:02'),
(133, 3, 19, 'Seeeeeshhhh!!', '2023-12-26 15:16:45'),
(134, 1, 19, 'What a nice!! 😍', '2024-01-04 14:41:36'),
(140, 2, 45, 'Yowww!', '2024-01-05 03:07:42'),
(141, 2, 40, '1', '2024-01-05 03:18:15'),
(142, 2, 40, ';;', '2024-01-05 03:46:51'),
(143, 2, 40, 'three', '2024-01-05 03:55:39'),
(144, 2, 40, 'four', '2024-01-05 03:56:12'),
(145, 2, 40, 'five', '2024-01-05 03:56:33'),
(146, 2, 68, 'q', '2024-01-05 03:59:15'),
(147, 2, 45, 'one test', '2024-01-05 03:59:38'),
(148, 1, 40, 'for 6', '2024-01-05 04:08:32'),
(149, 1, 68, '2', '2024-01-05 04:08:43'),
(150, 2, 1, 'HEY', '2024-01-05 04:11:15'),
(151, 16, 70, 'Heyyooo!', '2024-01-12 03:12:04'),
(154, 40, 70, 'sjdh', '2024-01-12 08:33:44'),
(158, 40, 70, 'aaa', '2024-01-12 08:37:04'),
(160, 41, 76, 'Hello', '2024-01-14 05:55:36'),
(162, 2, 77, 'When kaya?', '2024-01-14 06:03:28'),
(163, 2, 77, 'jjk', '2024-01-14 07:21:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int NOT NULL,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `artwork_id` int DEFAULT NULL,
  `notification_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notification_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `sender_id`, `receiver_id`, `artwork_id`, `notification_type`, `notification_data`, `is_read`, `created_at`) VALUES
(1, 3, 2, 5, 'Like', 'Juan  Dela Cruz unliked your artwork \'Pop Art\'.', 1, '2023-12-23 08:09:55'),
(2, 3, 2, 5, 'Like', 'Juan  Dela Cruz liked your artwork \'Pop Art\'.', 1, '2023-12-23 08:10:01'),
(4, 3, 2, 5, 'Like', 'Juan  Dela Cruz liked your artwork \'Pop Art\'.', 1, '2023-12-23 08:10:07'),
(5, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-23 08:10:33'),
(6, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-23 08:10:40'),
(7, 3, 2, 4, 'Like', 'Juan  Dela Cruz commented on your artwork \'Nature\'.', 1, '2023-12-23 11:51:30'),
(8, 2, 1, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 1, '2023-12-23 12:23:56'),
(9, 2, 1, NULL, 'follow', 'Jaren G. Quegan started following you.', 1, '2023-12-23 12:23:57'),
(10, 2, 1, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 1, '2023-12-23 12:23:58'),
(11, 2, 1, NULL, 'follow', 'Jaren G. Quegan started following you.', 1, '2023-12-23 12:23:59'),
(12, 2, 1, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 1, '2023-12-23 12:23:59'),
(13, 2, 1, NULL, 'follow', 'Jaren G. Quegan started following you.', 1, '2023-12-23 12:24:00'),
(14, 2, 1, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 1, '2023-12-23 12:24:01'),
(15, 2, 1, NULL, 'follow', 'Jaren G. Quegan started following you.', 1, '2023-12-23 12:24:01'),
(16, 2, 1, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 1, '2023-12-23 12:24:18'),
(17, 2, 1, NULL, 'follow', 'Jaren G. Quegan started following you.', 1, '2023-12-23 12:24:19'),
(18, 3, 1, 1, 'Like', 'Juan  Dela Cruz commented on your artwork \'Photography\'.', 1, '2023-12-23 13:40:31'),
(19, 3, 1, 1, 'Like', 'Juan  Dela Cruz commented on your artwork \'Photography\'.', 1, '2023-12-23 13:41:04'),
(20, 3, 1, 1, 'Like', 'Juan  Dela Cruz commented on your artwork \'Photography\'.', 1, '2023-12-23 13:49:43'),
(21, 3, 1, 1, 'Like', 'Juan  Dela Cruz commented on your artwork \'Photography\'.', 1, '2023-12-23 13:58:49'),
(22, 3, 1, 1, 'Like', 'Juan  Dela Cruz commented on your artwork \'Photography\'.', 1, '2023-12-23 14:19:24'),
(23, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-23 14:27:05'),
(24, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-23 14:27:06'),
(25, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-23 14:27:07'),
(26, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-23 14:27:07'),
(27, 3, 2, 7, 'Like', 'Juan  Dela Cruz unliked your artwork \'WItwiwww\'.', 1, '2023-12-23 14:27:10'),
(28, 3, 2, 7, 'Like', 'Juan  Dela Cruz liked your artwork \'WItwiwww\'.', 1, '2023-12-23 14:27:11'),
(29, 3, 2, 7, 'Like', 'Juan  Dela Cruz commented on your artwork \'WItwiwww\'.', 1, '2023-12-23 14:27:18'),
(30, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-23 14:55:56'),
(31, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-23 14:56:00'),
(32, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-23 14:56:10'),
(33, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-23 14:56:11'),
(34, 2, 3, NULL, 'follow', 'Jaren G. Quegan started following you.', 0, '2023-12-23 14:57:39'),
(35, 2, 3, NULL, 'follow', 'Jaren G. Quegan unfollowed you.', 0, '2023-12-24 00:12:14'),
(36, 2, 3, NULL, 'follow', 'Jaren G. Quegan started following you.', 0, '2023-12-24 00:12:15'),
(37, 1, 2, 6, 'Like', 'John R. Doe commented on your artwork \'Jaren Road\'.', 1, '2023-12-24 03:56:14'),
(38, 1, 2, 6, 'Like', 'John R. Doe commented on your artwork \'Jaren Road\'.', 1, '2023-12-24 03:56:39'),
(39, 1, 2, 5, 'Like', 'John R. Doe commented on your artwork \'Pop Art\'.', 1, '2023-12-24 13:12:27'),
(40, 2, 1, 1, 'Like', 'Jaren G. Quegan unliked your artwork \'Photography\'.', 1, '2023-12-25 13:50:54'),
(41, 2, 5, NULL, 'follow', 'Jaren G. Quegan started following you.', 0, '2023-12-25 14:38:05'),
(42, 2, 3, 2, 'Like', 'Jaren G. Quegan liked your artwork \'Steph Vs. LeBron\'.', 0, '2023-12-26 11:33:00'),
(43, 3, 11, NULL, 'follow', 'Juan  Dela Cruz started following you.', 0, '2023-12-26 11:53:23'),
(44, 3, 2, 17, 'Like', 'Juan  Dela Cruz liked your artwork \'Walang Title\'.', 1, '2023-12-26 11:53:35'),
(45, 2, 1, 1, 'Like', 'Jaren G. Quegan liked your artwork \'Photography\'.', 1, '2023-12-26 12:17:31'),
(46, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:29:47'),
(47, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-26 14:29:48'),
(48, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:32:19'),
(49, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-26 14:32:20'),
(50, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:38:49'),
(51, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-26 14:38:50'),
(52, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:40:19'),
(53, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-26 14:40:46'),
(54, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:42:45'),
(55, 3, 2, NULL, 'follow', 'Juan  Dela Cruz started following you.', 1, '2023-12-26 14:42:48'),
(56, 3, 2, NULL, 'follow', 'Juan  Dela Cruz unfollowed you.', 1, '2023-12-26 14:42:55'),
(57, 3, 1, 1, 'Like', 'Juan  Dela Cruz unliked your artwork \'Photography\'.', 1, '2023-12-26 14:47:17'),
(58, 13, 2, 19, 'Like', 'Johnny Y. Papa, Jr.  liked your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:11:07'),
(59, 13, 2, 19, 'Like', 'Johnny Y. Papa, Jr.  commented on your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:11:24'),
(60, 13, 2, NULL, 'follow', 'Johnny Y. Papa, Jr.  started following you.', 1, '2023-12-26 15:12:13'),
(61, 13, 2, 4, 'Like', 'Johnny Y. Papa, Jr.  liked your artwork \'Nature Keep Calm\'.', 1, '2023-12-26 15:12:23'),
(62, 13, 2, 4, 'Like', 'Johnny Y. Papa, Jr.  commented on your artwork \'Nature Keep Calm\'.', 1, '2023-12-26 15:12:39'),
(63, 5, 2, NULL, 'follow', 'Hazel B. Pinera started following you.', 1, '2023-12-26 15:13:38'),
(64, 5, 2, 19, 'Like', 'Hazel B. Pinera liked your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:13:44'),
(65, 11, 2, 19, 'Like', 'Chandler  Bing liked your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:14:35'),
(66, 11, 2, 19, 'Like', 'Chandler  Bing commented on your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:14:43'),
(67, 11, 2, NULL, 'follow', 'Chandler  Bing started following you.', 1, '2023-12-26 15:15:04'),
(68, 1, 2, 19, 'Like', 'John R. Doe liked your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:15:47'),
(69, 1, 2, 19, 'Like', 'John R. Doe commented on your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:16:02'),
(70, 3, 2, 19, 'Like', 'Juan  Dela Cruz liked your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:16:34'),
(71, 3, 2, 19, 'Like', 'Juan  Dela Cruz commented on your artwork \'Leap of Faith\'.', 1, '2023-12-26 15:16:45'),
(72, 2, 31, 60, 'Like', 'Jaren G. Quegan liked your artwork \'The Glandelinians Attack the Christian Nation\'.', 0, '2024-01-04 13:14:19'),
(73, 1, 2, 19, 'Like', 'John R. Doe commented on your artwork \'Leap of Faith\'.', 1, '2024-01-04 14:41:36'),
(74, 2, 24, 45, 'Like', 'Jaren G. Quegan liked your artwork \'Darkytown Rebellion\'.', 0, '2024-01-04 19:13:54'),
(75, 2, 24, 45, 'Like', 'Jaren  Quegan commented on your artwork \'Darkytown Rebellion\'.', 0, '2024-01-05 03:07:42'),
(76, 2, 21, 40, 'Like', 'Jaren  Quegan liked your artwork \'The Kiss\'.', 0, '2024-01-05 03:18:11'),
(77, 2, 21, 40, 'Like', 'Jaren  Quegan commented on your artwork \'The Kiss\'.', 0, '2024-01-05 03:18:15'),
(78, 2, 21, 40, 'Like', 'Jaren  Quegan commented on your artwork \'The Kiss\'.', 0, '2024-01-05 03:46:51'),
(79, 2, 21, 40, 'Like', 'Jaren  Quegan commented on your artwork \'The Kiss\'.', 0, '2024-01-05 03:55:39'),
(80, 2, 21, 40, 'Like', 'Jaren  Quegan unliked your artwork \'The Kiss\'.', 0, '2024-01-05 03:55:57'),
(81, 2, 21, 40, 'Like', 'Jaren  Quegan liked your artwork \'The Kiss\'.', 0, '2024-01-05 03:56:05'),
(82, 2, 21, 40, 'Like', 'Jaren  Quegan commented on your artwork \'The Kiss\'.', 0, '2024-01-05 03:56:12'),
(83, 2, 21, 40, 'Like', 'Jaren  Quegan commented on your artwork \'The Kiss\'.', 0, '2024-01-05 03:56:33'),
(84, 2, 17, 68, 'Like', 'Jaren  Quegan commented on your artwork \'Head in Blue Background\'.', 0, '2024-01-05 03:59:15'),
(85, 2, 24, 45, 'Like', 'Jaren  Quegan commented on your artwork \'Darkytown Rebellion\'.', 0, '2024-01-05 03:59:38'),
(86, 2, 17, 68, 'Like', 'Jaren  Quegan liked your artwork \'Head in Blue Background\'.', 0, '2024-01-05 04:08:03'),
(87, 1, 21, 40, 'Like', 'John R. Doe liked your artwork \'The Kiss\'.', 0, '2024-01-05 04:08:26'),
(88, 1, 21, 40, 'Like', 'John R. Doe commented on your artwork \'The Kiss\'.', 0, '2024-01-05 04:08:32'),
(89, 1, 17, 68, 'Like', 'John R. Doe commented on your artwork \'Head in Blue Background\'.', 0, '2024-01-05 04:08:43'),
(90, 1, 17, 68, 'Like', 'John R. Doe liked your artwork \'Head in Blue Background\'.', 0, '2024-01-05 04:08:49'),
(91, 2, 1, 1, 'Like', 'Jaren  Quegan commented on your artwork \'Photography\'.', 1, '2024-01-05 04:11:15'),
(92, 2, 1, NULL, 'follow', 'Jaren  Quegan unfollowed you.', 1, '2024-01-05 04:11:52'),
(93, 2, 1, NULL, 'follow', 'Jaren  Quegan started following you.', 1, '2024-01-05 04:12:01'),
(94, 2, 19, 30, 'Like', 'Jaren  Quegan liked your artwork \'Look Mickey\'.', 0, '2024-01-05 04:54:41'),
(95, 1, 2, 70, 'Like', 'John R. Doe liked your artwork \'Miles Morales\'.', 1, '2024-01-05 05:49:55'),
(102, 15, 2, 70, 'Like', 'Charles Thomas  Close liked your artwork \'Miles Morales\'.', 1, '2024-01-12 03:11:29'),
(109, 2, 37, NULL, 'follow', 'Jaren  Quegan started following you.', 0, '2024-01-12 03:14:35'),
(110, 2, 3, 13, 'Like', 'Jaren  Quegan liked your artwork \'Digi-Car\'.', 0, '2024-01-12 04:08:58'),
(123, 40, 2, NULL, 'follow', 'Gabriela   Mallillin started following you.', 0, '2024-01-12 08:32:19'),
(129, 40, 2, NULL, 'follow', 'Gabriela   Mallillin started following you.', 0, '2024-01-12 08:32:24'),
(130, 40, 2, 70, 'Like', 'Gabriela   Mallillin liked your artwork \'Miles Morales\'.', 0, '2024-01-12 08:32:38'),
(138, 2, 40, NULL, 'follow', 'Jaren  Quegan started following you.', 0, '2024-01-12 08:40:13'),
(140, 2, 42, NULL, 'follow', 'Jaren  Quegan started following you.', 1, '2024-01-14 05:54:10'),
(142, 43, 2, NULL, 'follow', 'da  Vin ci started following you.', 1, '2024-01-14 05:58:04'),
(143, 42, 37, NULL, 'follow', 'Jephunneh  Tugas started following you.', 0, '2024-01-14 05:58:12'),
(144, 42, 37, NULL, 'follow', 'Jephunneh  Tugas unfollowed you.', 0, '2024-01-14 05:58:13'),
(145, 42, 37, NULL, 'follow', 'Jephunneh  Tugas started following you.', 0, '2024-01-14 05:58:13'),
(146, 2, 43, NULL, 'follow', 'Jaren  Quegan started following you.', 1, '2024-01-14 05:58:14'),
(147, 42, 37, NULL, 'follow', 'Jephunneh  Tugas unfollowed you.', 0, '2024-01-14 05:58:15'),
(148, 42, 37, NULL, 'follow', 'Jephunneh  Tugas started following you.', 0, '2024-01-14 05:58:25'),
(149, 42, 37, NULL, 'follow', 'Jephunneh  Tugas unfollowed you.', 0, '2024-01-14 05:58:27'),
(150, 42, 37, NULL, 'follow', 'Jephunneh  Tugas started following you.', 0, '2024-01-14 05:58:28'),
(151, 42, 37, NULL, 'follow', 'Jephunneh  Tugas unfollowed you.', 0, '2024-01-14 05:58:28'),
(152, 42, 43, 77, 'Like', 'Jephunneh  Tugas liked your artwork \'Testing 101\'.', 0, '2024-01-14 06:01:41'),
(153, 2, 43, 77, 'Like', 'Jaren  Quegan liked your artwork \'Testing 101\'.', 0, '2024-01-14 06:02:25'),
(154, 41, 43, 77, 'Like', 'Stephen Keant  Castaneda liked your artwork \'Testing 101\'.', 0, '2024-01-14 06:03:14'),
(155, 41, 43, 77, 'Like', 'Stephen Keant  Castaneda commented on your artwork \'Testing 101\'.', 0, '2024-01-14 06:03:21'),
(156, 2, 43, 77, 'Like', 'Jaren  Quegan commented on your artwork \'Testing 101\'.', 0, '2024-01-14 06:03:28'),
(158, 2, 41, NULL, 'follow', 'Jaren  Quegan unfollowed you.', 1, '2024-01-14 06:09:23'),
(159, 2, 41, NULL, 'follow', 'Jaren  Quegan started following you.', 1, '2024-01-14 06:09:23'),
(160, 2, 42, 78, 'Like', 'Jaren  Quegan liked your artwork \'Kyot\'.', 0, '2024-01-14 06:13:39'),
(161, 42, 33, 64, 'Like', 'Jephunneh Jediael A. Tugas liked your artwork \'Seville\'.', 0, '2024-01-14 06:19:32'),
(162, 2, 42, 79, 'Like', 'Jaren  Quegan liked your artwork \'Meme\'.', 0, '2024-01-14 07:16:52'),
(163, 2, 43, NULL, 'follow', 'Jaren  Quegan unfollowed you.', 0, '2024-01-14 07:17:50'),
(164, 2, 43, NULL, 'follow', 'Jaren  Quegan started following you.', 0, '2024-01-14 07:17:51'),
(165, 2, 43, 77, 'Like', 'Jaren  Quegan commented on your artwork \'Testing 101\'.', 0, '2024-01-14 07:21:44'),
(166, 2, 43, 77, 'Like', 'Jaren  Quegan liked your artwork \'Testing 101\'.', 0, '2024-01-14 07:21:49'),
(167, 2, 42, NULL, 'follow', 'Jaren  Quegan unfollowed you.', 0, '2024-01-14 07:22:25'),
(168, 2, 42, NULL, 'follow', 'Jaren  Quegan started following you.', 0, '2024-01-14 07:22:26'),
(169, 2, 42, NULL, 'follow', 'Jaren  Quegan unfollowed you.', 0, '2024-01-14 07:22:26'),
(170, 2, 42, NULL, 'follow', 'Jaren  Quegan started following you.', 0, '2024-01-14 07:22:31'),
(171, 41, 2, 70, 'Like', 'Stephen Keant  Castaneda liked your artwork \'Miles Morales\'.', 0, '2024-01-14 08:29:59'),
(172, 41, 2, NULL, 'follow', 'Stephen Keant  Castaneda started following you.', 0, '2024-01-14 08:30:07'),
(173, 41, 2, 4, 'Like', 'Stephen Keant  Castaneda liked your artwork \'Nature Keep Calm\'.', 0, '2024-01-14 08:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `qr_code`
--

CREATE TABLE `qr_code` (
  `qr_code_id` int NOT NULL,
  `donation_text` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `account_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_img` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_code`
--

INSERT INTO `qr_code` (`qr_code_id`, `donation_text`, `account_name`, `account_no`, `qr_code_img`) VALUES
(1, 'If you appreciate the value of VIVIDLY and wish to contribute to its ongoing development and maintenance, <br>you have the option to make a donation by scanning the QR code provided below.', 'Jaren Quegan', '3706308112 (LandBank)', 'Untitled design_20231208_173724_0000.png');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int NOT NULL,
  `artist_id` int DEFAULT NULL,
  `username` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middlename` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suffix` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emailaddress` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `artist_pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `artist_id`, `username`, `firstname`, `middlename`, `lastname`, `suffix`, `emailaddress`, `artist_pic`, `review_content`, `created_at`) VALUES
(29, 2, 'jarenquegan', 'Jaren', 'Gutierrez', 'Quegan', '', 'queganjaren@gmail.com', 'IMG_229.jpg', 'Gwapoo!', '2024-01-04 19:07:57'),
(30, 2, 'jarenquegan', 'Jaren', '', 'Quegan', '', 'queganjaren@gmail.com', 'IMG_229.jpg', 'Whoah! This is very nice! Good luck on your presentation! 🤘🏻', '2024-01-13 00:46:27');

-- --------------------------------------------------------

--
-- Table structure for table `social_accounts`
--

CREATE TABLE `social_accounts` (
  `social_id` int NOT NULL,
  `facebook` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `linkedin` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_accounts`
--

INSERT INTO `social_accounts` (`social_id`, `facebook`, `twitter`, `instagram`, `linkedin`) VALUES
(1, 'https://web.facebook.com/jaren.quegan/', 'https://twitter.com/JarenQuegan', 'https://www.instagram.com/jarenquegan/', 'https://www.linkedin.com/in/jaren-quegan-24952b164');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`);

--
-- Indexes for table `artists_artworks`
--
ALTER TABLE `artists_artworks`
  ADD PRIMARY KEY (`artist_id`,`artwork_id`),
  ADD KEY `artwork_id` (`artwork_id`);

--
-- Indexes for table `artist_followers`
--
ALTER TABLE `artist_followers`
  ADD PRIMARY KEY (`follower_id`,`artist_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `artist_liked_artworks`
--
ALTER TABLE `artist_liked_artworks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `artwork_id` (`artwork_id`);

--
-- Indexes for table `artworks`
--
ALTER TABLE `artworks`
  ADD PRIMARY KEY (`artwork_id`);

--
-- Indexes for table `branding`
--
ALTER TABLE `branding`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `artwork_id` (`artwork_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `artwork_id` (`artwork_id`);

--
-- Indexes for table `qr_code`
--
ALTER TABLE `qr_code`
  ADD PRIMARY KEY (`qr_code_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`artist_id`);

--
-- Indexes for table `social_accounts`
--
ALTER TABLE `social_accounts`
  ADD PRIMARY KEY (`social_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `artist_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `artist_liked_artworks`
--
ALTER TABLE `artist_liked_artworks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1096;

--
-- AUTO_INCREMENT for table `artworks`
--
ALTER TABLE `artworks`
  MODIFY `artwork_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `branding`
--
ALTER TABLE `branding`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `qr_code`
--
ALTER TABLE `qr_code`
  MODIFY `qr_code_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `social_accounts`
--
ALTER TABLE `social_accounts`
  MODIFY `social_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artists_artworks`
--
ALTER TABLE `artists_artworks`
  ADD CONSTRAINT `artists_artworks_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `artists_artworks_ibfk_2` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`artwork_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `artist_followers`
--
ALTER TABLE `artist_followers`
  ADD CONSTRAINT `artist_followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `artist_followers_ibfk_2` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `artist_liked_artworks`
--
ALTER TABLE `artist_liked_artworks`
  ADD CONSTRAINT `artist_liked_artworks_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `artist_liked_artworks_ibfk_2` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`artwork_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`artwork_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `artists` (`artist_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`artwork_id`) REFERENCES `artworks` (`artwork_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
