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
-- Структура таблицы `entertainment_list`
--

CREATE TABLE `entertainment_list` (
  `id` int(11) NOT NULL,
  `title` varchar(30) DEFAULT NULL,
  `parent_entertainment_id` int(11) DEFAULT NULL,
  `priority` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `entertainment_list`
--

INSERT INTO `entertainment_list` (`id`, `title`, `parent_entertainment_id`, `priority`) VALUES
(1, 'Аквопарк', NULL, NULL),
(2, 'Велопрокат', NULL, NULL),
(3, 'Дайвинг', NULL, NULL),
(4, 'Кинотеатр/театр', NULL, NULL),
(5, 'Концертный зал', NULL, NULL),
(6, 'Морские прогулки', NULL, NULL),
(7, 'Музеи', NULL, NULL),
(8, 'Океанариум', NULL, NULL),
(9, 'Парк аттракционов', NULL, NULL),
(10, 'Пейнтбол', NULL, NULL),
(11, 'Пляжные развлечения', NULL, NULL),
(12, 'Прогулки на квадроциклах', NULL, NULL),
(13, 'Конные прогулки', NULL, NULL),
(14, 'Экскурсии', NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `entertainment_list`
--
ALTER TABLE `entertainment_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `entertainment_list`
--
ALTER TABLE `entertainment_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
