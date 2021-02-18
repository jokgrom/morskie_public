-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Фев 18 2021 г., 18:53
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
-- Структура таблицы `photo_entertainment`
--

CREATE TABLE `photo_entertainment` (
  `id` int(11) NOT NULL,
  `_adminStatusPublication` int(11) NOT NULL DEFAULT 3,
  `date_added` int(250) NOT NULL,
  `name` varchar(250) NOT NULL,
  `person_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `sizeWidth` varchar(9) DEFAULT NULL,
  `sizeHeight` varchar(9) DEFAULT NULL,
  `sizeBytes` varchar(9) DEFAULT NULL,
  `path` varchar(200) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `photo_entertainment`
--
ALTER TABLE `photo_entertainment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `photo_entertainment`
--
ALTER TABLE `photo_entertainment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
