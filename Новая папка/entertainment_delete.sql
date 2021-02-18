-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 18 2021 г., 18:52
-- Версия сервера: 10.3.22-MariaDB
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `jokgrom_morskie`
--

-- --------------------------------------------------------

--
-- Структура таблицы `entertainment_delete`
--

CREATE TABLE `entertainment_delete` (
  `id` int(11) NOT NULL,
  `publicationStatus_id` int(5) DEFAULT NULL,
  `_adminStatusPublication` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  `date_edit` timestamp NULL DEFAULT NULL,
  `date_actual` timestamp NULL DEFAULT NULL,
  `person_id` int(25) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `city_id` int(25) DEFAULT NULL,
  `suburb_id` int(25) DEFAULT NULL,
  `entertainment` int(25) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prices` text DEFAULT NULL,
  `addressLatitude` varchar(25) DEFAULT NULL,
  `addressLongitude` varchar(25) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `infoError` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `entertainment_delete`
--
ALTER TABLE `entertainment_delete`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `entertainment_delete`
--
ALTER TABLE `entertainment_delete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
