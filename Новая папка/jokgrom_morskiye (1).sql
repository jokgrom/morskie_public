-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 26 2021 г., 10:22
-- Версия сервера: 10.3.22-MariaDB-log
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `jokgrom_morskiye`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ad_owner`
--

CREATE TABLE `ad_owner` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ad_owner`
--

INSERT INTO `ad_owner` (`id`, `title`) VALUES
(1, 'собственник'),
(2, 'риелтор');

-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `parent_city_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `city`
--

INSERT INTO `city` (`id`, `title`, `parent_city_id`) VALUES
(1, 'Анапа', NULL),
(2, 'Геленджик', NULL),
(3, 'Новороссийск', NULL),
(4, 'Сочи', NULL),
(5, 'Темрюк', NULL),
(6, 'Туапсе', NULL),
(7, 'Архипо-Осиповка', 2),
(8, 'Кабардинка', 2),
(9, 'Дивноморское', 2),
(10, 'Марьина Роща', 2),
(11, 'Береговое', 2),
(12, 'Бетта', 2),
(13, 'Тешебс', 2),
(14, 'Светлый', 2),
(15, 'Джанхот', 2),
(16, 'Прасковеевка', 2),
(17, 'Виноградное', 2),
(18, 'Криница', 2),
(19, 'Абрау-Дюрсо', 3),
(20, 'Глебовское	', 3),
(21, 'Дюрсо', 3),
(22, 'Мысхако', 3),
(23, 'Северная Озереевка', 3),
(24, 'Федотовка', 3),
(25, 'Широкая Балка', 3),
(26, 'Южная Озереевка', 3),
(27, 'Джубга', 6),
(28, 'Лермонтово', 6),
(29, 'Сосновый', 6),
(30, 'Майский', 6),
(31, 'Тенгинка', 6),
(32, 'Пляхо', 6),
(33, 'Новомихайловский', 6),
(34, 'Ольгинка', 6),
(35, 'Тюменский', 6),
(36, 'Небуг', 6),
(37, 'Агой', 6),
(38, 'Агуй-Шапсуг', 6),
(39, 'Шепси', 6),
(40, 'Гизель-Дере', 6),
(41, 'Дедеркой', 6),
(42, 'Артющенко', 5),
(43, 'Ахтанизовская', 5),
(44, 'Батарейка', 5),
(45, 'Береговой', 5),
(46, 'Веселовка', 5),
(47, 'Виноградный', 5),
(48, 'Волна', 5),
(49, 'Волна Революции', 5),
(50, 'Вышестеблиевская', 5),
(51, 'Гаркуша', 5),
(52, 'Голубицкая', 5),
(53, 'За Родину', 5),
(54, 'Запорожская', 5),
(55, 'Ильич', 5),
(56, 'Красноармейский', 5),
(57, 'Кучугуры', 5),
(58, 'Октябрьский', 5),
(59, 'Пересыпь', 5),
(60, 'Приазовский', 5),
(61, 'Приморский', 5),
(62, 'Прогресс', 5),
(63, 'Сенной', 5),
(64, 'Солёный', 5),
(65, 'Таманский', 5),
(66, 'Тамань', 5),
(67, 'Темрюк', 5),
(68, 'Фонталовская', 5),
(69, 'Юбилейный', 5),
(70, 'Чембурка', 1),
(71, 'Варваровка', 1),
(72, 'Су-Псех', 1),
(73, 'Гай-Кодзор', 1),
(74, 'Анапская', 1),
(75, 'Бужор', 1),
(76, 'Просторный', 1),
(77, 'Виноградный', 1),
(78, 'Пятихатки', 1),
(79, 'Джигинка', 1),
(80, 'Джемете', 1),
(81, 'Усатова Балка', 1),
(82, 'Цыбанобалка', 1),
(83, 'Песчаный', 1),
(84, 'Красный', 1),
(90, 'Витязево', 1),
(91, 'Благовещенская', 1),
(92, 'Большой Утриш', 1),
(93, 'Воскресенский', 1),
(94, 'Сукко', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `conveniences`
--

CREATE TABLE `conveniences` (
  `id` int(20) NOT NULL,
  `title` varchar(30) DEFAULT NULL,
  `parent_convenience_id` int(11) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `conveniences`
--

INSERT INTO `conveniences` (`id`, `title`, `parent_convenience_id`, `priority`) VALUES
(1, 'душ', 37, NULL),
(2, 'удалить', 37, NULL),
(3, 'ванная', 37, NULL),
(4, 'терраса/балкон', 37, NULL),
(6, 'вид на море', 37, NULL),
(7, 'тихое место', 37, NULL),
(8, 'звукоизоляция', 37, NULL),
(9, 'с питанием', 40, NULL),
(10, 'кондиционер/сплит', 38, NULL),
(11, 'кухня/миникухня', 37, NULL),
(13, 'wi-fi/интернет', 38, NULL),
(14, 'ТВ', 38, NULL),
(15, 'кабельное ТВ', 38, NULL),
(16, 'чайник', 38, NULL),
(17, 'сейф', 38, NULL),
(18, 'холодильник', 38, NULL),
(19, 'микроволновка', 38, NULL),
(20, 'плита', 38, NULL),
(21, 'стиральная машинка', 38, NULL),
(22, 'фен', 38, NULL),
(23, 'детская площадка', 39, NULL),
(24, 'бассейн', 39, NULL),
(25, 'баня/сауна', 39, NULL),
(26, 'столовая/кафе', 39, NULL),
(27, 'мангал/BBQ', 39, NULL),
(28, 'тенис/бильярд', 39, NULL),
(29, 'фитнес центр', 39, NULL),
(30, 'парковка/автостоянка', 39, NULL),
(31, 'удалить наверное', 39, NULL),
(32, 'с животными', 40, NULL),
(33, 'с детьми', 40, NULL),
(34, 'номер для курящих', 40, NULL),
(35, 'трансфер', 40, NULL),
(36, 'удобства для инвалидов', 40, NULL),
(37, 'Удобства', NULL, 2),
(38, 'Удобства в номере', NULL, 4),
(39, 'На территории', NULL, 6),
(40, 'Другие удобства', NULL, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `distance`
--

CREATE TABLE `distance` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `distance`
--

INSERT INTO `distance` (`id`, `title`) VALUES
(1, '~ 300м'),
(2, '~ 500м'),
(3, '~ 1км'),
(4, '~ 1.5км'),
(5, '~ 2км'),
(6, '3км и более');

-- --------------------------------------------------------

--
-- Структура таблицы `guest`
--

CREATE TABLE `guest` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `guest`
--

INSERT INTO `guest` (`id`, `title`) VALUES
(1, '1 человек'),
(2, '2 человека'),
(3, '3 человека'),
(4, '4 человека'),
(5, '5 человек'),
(6, 'более 5 человек');

-- --------------------------------------------------------

--
-- Структура таблицы `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `identification` varchar(25) DEFAULT NULL,
  `date_registration` int(13) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `mail` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `person`
--

INSERT INTO `person` (`id`, `phone`, `password`, `identification`, `date_registration`, `ip`, `name`, `mail`) VALUES
(103, '75666666666', '$2y$10$gMPNZWhcunQOWNHX0wFMuehIRNcqvBLjmSslZRvH.2ak/BDdmu/M2', '41e2357566238ece4d6589d15', 1611167142, '127.0.0.1', NULL, NULL),
(104, '76766666666', '$2y$10$CKEVRnLobofGwIfTttXpTu92btYSeuIYXBLpcMv7lBbclI5A.edPW', '0dfa2e567a5f7bea7b6b0e6e6', 1611182568, '127.0.0.1', NULL, NULL),
(105, '74545555555', '$2y$10$MX4J8bvhXXXgepmtFwFnpeju2ikvuOqwPehFNmtSIccoJJ9ihS2Ua', '567d5ae16461b16a61164b674', 1611182935, '127.0.0.1', NULL, NULL),
(106, '76343434343', '$2y$10$wegXbljwnUAsUHRrC1tDPeDFeysJRZvj925xWFApUtFpMWxAxP8FO', 'd5eb2f3c4ba4514a1d2c8b64c', 1611253822, '127.0.0.1', NULL, NULL),
(107, '74433434344', '$2y$10$siMPSb8oOzuQyf5pzEWnxuA/sZU2f7Xrxvnxald6NnO6pfmFmP1uW', 'e5f8f4744dd9094f69f99795d', 1611256612, '127.0.0.1', NULL, NULL),
(108, '74344343434', '$2y$10$dNmVOjLI8dkIF8w66KRZGeCO70oSKNf0ZmxVO373603aly3Q0qycm', 'a7f2afb3c4685697850421074', 1611414224, '127.0.0.1', NULL, NULL),
(109, '72333333333', '$2y$10$TfzD6dMa3uRr9aqTHvhxK.PJcmlOE/MrMjBQ60xNmewDH5yCVcsNq', '2a88c284f9bbe50c412a7f95f', 1611518931, '127.0.0.1', NULL, NULL),
(110, '79186646763', '$2y$10$m4wXYp/dxc1zrhmbJsEFkOXDPU1/sbDnrcAxRVg.m5cZjoWbGJ9HC', 'b0e61cf8a37af3d03a576d3f9', 1611587621, '127.0.0.1', 'володия', 'volofi@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `person_edit`
--

CREATE TABLE `person_edit` (
  `id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `identification` varchar(25) DEFAULT NULL,
  `date_edit` int(13) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `mail` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `person_edit`
--

INSERT INTO `person_edit` (`id`, `person_id`, `phone`, `identification`, `date_edit`, `ip`, `name`, `mail`) VALUES
(1, 110, '79186646763', NULL, NULL, NULL, NULL, NULL),
(2, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', NULL, NULL, NULL, NULL),
(3, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588047, NULL, NULL, NULL),
(4, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588047, '127.0.0.1', NULL, NULL),
(5, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588552, '127.0.0.1', '', ''),
(6, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588552, '127.0.0.1', '', ''),
(7, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588823, '127.0.0.1', '', ''),
(8, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611588958, '127.0.0.1', 'егорка', 'dsds@mail.ru'),
(9, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611589054, '127.0.0.1', 'володия', 'volofi@mail.ru'),
(10, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611589104, '127.0.0.1', 'володия', 'volofi@mail.ru'),
(11, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611589131, '127.0.0.1', 'володияыы', 'volofi@mail.ru'),
(12, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611589158, '127.0.0.1', 'володи', 'volofi@mail.ru'),
(13, 110, '79186646763', 'b0e61cf8a37af3d03a576d3f9', 1611589173, '127.0.0.1', 'володиуууу', 'volofi@mail.ru');

-- --------------------------------------------------------

--
-- Структура таблицы `photo_residence`
--

CREATE TABLE `photo_residence` (
  `id` int(11) NOT NULL,
  `_adminStatusPublication` int(11) DEFAULT 3,
  `date_added` int(250) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `residence_id` int(11) DEFAULT NULL,
  `sizeWidth` varchar(5) DEFAULT NULL,
  `sizeHeight` varchar(5) DEFAULT NULL,
  `sizeBytes` varchar(9) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `photo_residence`
--

INSERT INTO `photo_residence` (`id`, `_adminStatusPublication`, `date_added`, `name`, `person_id`, `residence_id`, `sizeWidth`, `sizeHeight`, `sizeBytes`, `path`, `priority`) VALUES
(511, 2, 1611417349, '0906ee373a11611417349.jpg', 108, 192, '1080', '720', '248821', '/photo/108/192/', 1),
(512, 2, 1611417349, '0de7b5dd5df1611417349.jpg', 108, 192, '1600', '1063', '520479', '/photo/108/192/', 2),
(513, 3, 1611421096, '9906ee373a11611421096.jpg', 108, 193, '1080', '720', '248821', '/photo/108/193/', 1),
(514, 3, 1611421096, '3de7b5dd5df1611421096.jpg', 108, 193, '1600', '1063', '520479', '/photo/108/193/', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `photo_residence_delete`
--

CREATE TABLE `photo_residence_delete` (
  `id` int(11) NOT NULL,
  `_adminStatusPublication` int(11) DEFAULT NULL,
  `date_added` int(250) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `residence_id` int(11) DEFAULT NULL,
  `sizeWidth` varchar(5) DEFAULT NULL,
  `sizeHeight` varchar(5) DEFAULT NULL,
  `sizeBytes` varchar(9) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `publication_status`
--

CREATE TABLE `publication_status` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titleFromAdmin` varchar(50) NOT NULL,
  `_comment` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `publication_status`
--

INSERT INTO `publication_status` (`id`, `title`, `titleFromAdmin`, `_comment`) VALUES
(1, 'опубликовать', 'снять с публикации', NULL),
(2, 'отклонено', 'одобрено', 'на модерации'),
(3, 'снято с публикации', 'опубликовано', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `residence`
--

CREATE TABLE `residence` (
  `id` int(11) NOT NULL,
  `publicationStatus_id` int(5) DEFAULT 2,
  `_adminStatusPublication` int(11) NOT NULL DEFAULT 3,
  `date_added` int(250) DEFAULT NULL,
  `person_id` int(25) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `city_id` int(25) DEFAULT NULL,
  `suburb_id` int(25) DEFAULT NULL,
  `guest_id` int(25) DEFAULT NULL,
  `typeHousing_id` int(25) DEFAULT NULL,
  `distance_id` int(25) DEFAULT NULL,
  `adOwner_id` int(25) DEFAULT NULL,
  `rules` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `addressLatitude` varchar(25) DEFAULT NULL,
  `addressLongitude` varchar(25) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `conveniences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `infoError` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `residence`
--

INSERT INTO `residence` (`id`, `publicationStatus_id`, `_adminStatusPublication`, `date_added`, `person_id`, `title`, `city_id`, `suburb_id`, `guest_id`, `typeHousing_id`, `distance_id`, `adOwner_id`, `rules`, `description`, `addressLatitude`, `addressLongitude`, `address`, `contacts`, `conveniences`, `prices`, `infoError`) VALUES
(192, 2, 2, 1611417340, 108, 'dq d22 четы пятьь шестьь дв три два', 2, 7, 1, 1, 3, 1, 'eqweqw', 'qweqe', '44.894965', '37.31617', 'Россия, Краснодарский край, Анапа', 'rjynfrns', '[3,6,22,18,35,36]', '{\"1\":0,\"2\":0,\"3\":333,\"4\":0,\"5\":0,\"6\":0,\"7\":0,\"8\":554,\"9\":0,\"10\":0,\"11\":0,\"12\":0}', NULL),
(193, 2, 3, 1611421079, 108, 'vsd', 2, 7, 1, 1, 4, 2, 'rewerw', 'wreew', '44.894965', '37.31617', 'Россия, Краснодарский край, Анапа', 'rjynfrns', '[3,6,19,20]', '{\"1\":333,\"2\":0,\"3\":443,\"4\":0,\"5\":0,\"6\":0,\"7\":0,\"8\":0,\"9\":0,\"10\":0,\"11\":0,\"12\":0}', NULL),
(194, 2, 3, 1611514846, 108, 'dassda', 3, 20, 2, 1, 1, 1, 'sdasd', 'sdadw', '44.894965', '37.31617', 'Россия, Краснодарский край, Анапа', 'rjynfrns', '[3,6,19,20]', '{\"1\":332,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":444,\"7\":0,\"8\":0,\"9\":0,\"10\":0,\"11\":0,\"12\":0}', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `residence_delete`
--

CREATE TABLE `residence_delete` (
  `id` int(11) NOT NULL,
  `publicationStatus_id` int(5) DEFAULT 2,
  `_adminStatusPublication` int(11) NOT NULL DEFAULT 3,
  `date_added` int(250) DEFAULT NULL,
  `person_id` int(25) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `city_id` int(25) DEFAULT NULL,
  `suburb_id` int(25) DEFAULT NULL,
  `guest_id` int(25) DEFAULT NULL,
  `typeHousing_id` int(25) DEFAULT NULL,
  `distance_id` int(25) DEFAULT NULL,
  `adOwner_id` int(25) DEFAULT NULL,
  `rules` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `addressLatitude` varchar(25) DEFAULT NULL,
  `addressLongitude` varchar(25) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `conveniences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `infoError` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `residence_edit`
--

CREATE TABLE `residence_edit` (
  `id` int(11) NOT NULL,
  `residence_id` int(11) NOT NULL,
  `publicationStatus_id` int(5) DEFAULT 2,
  `_adminStatusPublication` int(11) NOT NULL DEFAULT 3,
  `date_edit` int(250) DEFAULT NULL,
  `person_id` int(25) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `city_id` int(25) DEFAULT NULL,
  `suburb_id` int(25) DEFAULT NULL,
  `guest_id` int(25) DEFAULT NULL,
  `typeHousing_id` int(25) DEFAULT NULL,
  `distance_id` int(25) DEFAULT NULL,
  `adOwner_id` int(25) DEFAULT NULL,
  `rules` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `addressLatitude` varchar(25) DEFAULT NULL,
  `addressLongitude` varchar(25) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `conveniences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `prices` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `infoError` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `residence_edit`
--

INSERT INTO `residence_edit` (`id`, `residence_id`, `publicationStatus_id`, `_adminStatusPublication`, `date_edit`, `person_id`, `title`, `city_id`, `suburb_id`, `guest_id`, `typeHousing_id`, `distance_id`, `adOwner_id`, `rules`, `description`, `addressLatitude`, `addressLongitude`, `address`, `contacts`, `conveniences`, `prices`, `infoError`) VALUES
(85, 192, 2, 3, 1611421079, 108, 'vsd redact', 2, 7, 1, 1, 4, 2, 'rewerw', 'wreew', '44.894965', '37.31617', 'Россия, Краснодарский край, Анапа', 'rjynfrns', '[3,6,19,20]', '{\"1\":333,\"2\":0,\"3\":443,\"4\":0,\"5\":0,\"6\":0,\"7\":0,\"8\":0,\"9\":0,\"10\":0,\"11\":0,\"12\":0}', NULL),
(86, 194, 2, 3, 1611514846, 108, 'dassda', 3, 20, 2, 1, 1, 1, 'sdasd', 'sdadw', '44.894965', '37.31617', 'Россия, Краснодарский край, Анапа', 'rjynfrns', '[3,6,19,20]', '{\"1\":332,\"2\":0,\"3\":0,\"4\":0,\"5\":0,\"6\":444,\"7\":0,\"8\":0,\"9\":0,\"10\":0,\"11\":0,\"12\":0}', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `suburb`
--

CREATE TABLE `suburb` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `city_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `suburb`
--

INSERT INTO `suburb` (`id`, `title`, `type`, `city_id`) VALUES
(1, 'Архипо-Осиповка', 'село', 2),
(2, 'Кабардинка', 'село', 2),
(3, 'Дивноморское', 'село', 2),
(4, 'Марьина Роща', 'село', 2),
(5, 'Береговое', 'село', 2),
(6, 'Бетта', 'хутор', 2),
(7, 'Тешебс', 'село', 2),
(8, 'Светлый', 'посёлок', 2),
(9, 'Джанхот', 'хутор', 2),
(10, 'Прасковеевка', 'село', 2),
(11, 'Виноградное', 'село', 2),
(12, 'Криница', 'село', 2),
(13, 'Абрау-Дюрсо', 'село', 3),
(14, 'Глебовское	', 'село', 3),
(15, 'Дюрсо	', 'хутор', 3),
(16, 'Мысхако	', 'село', 3),
(17, 'Северная Озереевка', 'село', 3),
(18, 'Федотовка	', 'село', 3),
(19, 'Широкая Балка', 'село', 3),
(20, 'Южная Озереевка', 'село', 3),
(21, 'Джубга', 'посёлок', 6),
(22, 'Лермонтово', 'посёлок', 6),
(23, 'Сосновый', 'посёлок', 6),
(24, 'Майский', 'посёлок', 6),
(25, 'Тенгинка', 'село', 6),
(26, 'Пляхо', 'посёлок', 6),
(27, 'Новомихайловский', 'посёлок', 6),
(29, 'Ольгинка', 'посёлок', 6),
(30, 'Тюменский', 'посёлок', 6),
(31, 'Небуг', 'село', 6),
(32, 'Агой', 'село', 6),
(33, 'Агуй-Шапсуг', 'аул', 6),
(34, 'Шепси', 'село', 6),
(35, 'Гизель-Дере', 'посёлок', 6),
(36, 'Дедеркой', 'посёлок', 6),
(37, 'около центра', NULL, 1),
(38, 'около центра', NULL, 2),
(39, 'около центра', NULL, 3),
(40, 'около центра', NULL, 4),
(41, 'около центра', NULL, 5),
(42, 'около центра', NULL, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `type_housing`
--

CREATE TABLE `type_housing` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type_housing`
--

INSERT INTO `type_housing` (`id`, `title`) VALUES
(1, 'Гостевые дома'),
(2, 'Дома/коттеджи под ключ'),
(3, 'Квартиры под ключ'),
(4, 'Гостиницы/отели'),
(5, 'Кемпинг'),
(6, 'База отдыха'),
(7, 'Частный сектор');

-- --------------------------------------------------------

--
-- Структура таблицы `type_relaxation`
--

CREATE TABLE `type_relaxation` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type_relaxation`
--

INSERT INTO `type_relaxation` (`id`, `title`) VALUES
(1, 'Аквопарк'),
(2, 'Велопрокат'),
(3, 'Дайвинг'),
(4, 'Кинотеатр/театр'),
(5, 'Концертный зал'),
(6, 'Морские прогулки'),
(7, 'Музеи'),
(8, 'Океанариум'),
(9, 'Парк аттракционов'),
(10, 'Пейнтбол'),
(11, 'Пляжные развлечения'),
(12, 'Прогулки на квадроциклах'),
(13, 'Конные прогулки'),
(14, 'Экскурсии');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ad_owner`
--
ALTER TABLE `ad_owner`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_city_id_fk` (`parent_city_id`);

--
-- Индексы таблицы `conveniences`
--
ALTER TABLE `conveniences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `convenience_convenience_id_fk` (`parent_convenience_id`);

--
-- Индексы таблицы `distance`
--
ALTER TABLE `distance`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Индексы таблицы `person_edit`
--
ALTER TABLE `person_edit`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_residence`
--
ALTER TABLE `photo_residence`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `photo_residence_delete`
--
ALTER TABLE `photo_residence_delete`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `publication_status`
--
ALTER TABLE `publication_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `residence`
--
ALTER TABLE `residence`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `residence_delete`
--
ALTER TABLE `residence_delete`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `residence_edit`
--
ALTER TABLE `residence_edit`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `suburb`
--
ALTER TABLE `suburb`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `type_housing`
--
ALTER TABLE `type_housing`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `type_relaxation`
--
ALTER TABLE `type_relaxation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ad_owner`
--
ALTER TABLE `ad_owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT для таблицы `conveniences`
--
ALTER TABLE `conveniences`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `distance`
--
ALTER TABLE `distance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT для таблицы `person_edit`
--
ALTER TABLE `person_edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `photo_residence`
--
ALTER TABLE `photo_residence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=515;

--
-- AUTO_INCREMENT для таблицы `photo_residence_delete`
--
ALTER TABLE `photo_residence_delete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=504;

--
-- AUTO_INCREMENT для таблицы `publication_status`
--
ALTER TABLE `publication_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `residence`
--
ALTER TABLE `residence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT для таблицы `residence_delete`
--
ALTER TABLE `residence_delete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT для таблицы `residence_edit`
--
ALTER TABLE `residence_edit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT для таблицы `suburb`
--
ALTER TABLE `suburb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `type_housing`
--
ALTER TABLE `type_housing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `type_relaxation`
--
ALTER TABLE `type_relaxation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `city_city_id_fk` FOREIGN KEY (`parent_city_id`) REFERENCES `city` (`id`);

--
-- Ограничения внешнего ключа таблицы `conveniences`
--
ALTER TABLE `conveniences`
  ADD CONSTRAINT `convenience_convenience_id_fk` FOREIGN KEY (`parent_convenience_id`) REFERENCES `conveniences` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
