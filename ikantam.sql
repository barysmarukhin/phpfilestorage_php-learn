-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 02 2016 г., 17:34
-- Версия сервера: 5.5.45
-- Версия PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ikantam`
--

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `usermail` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `filename`, `type`, `size`, `caption`, `usermail`) VALUES
(1, 'autohub.png', 'image/png', 451901, 'тестовая страничка autohub', 'admin@admin.com'),
(2, 'second.png', 'image/png', 98626, 'Второй проект loftschool', 'admin@admin.com'),
(3, 'first.png', 'image/png', 91524, 'Первый проект loftschool', 'mail@mail.com'),
(4, 'third.png', 'image/png', 288134, 'Третий проект loftschool', 'mail@mail.com'),
(5, 'postcsslayout.png', 'image/png', 401129, 'тестовая страница postscclayout', 'mail@mail.com'),
(6, 'webcomsite.png', 'image/png', 328864, 'тестовая страничка webcomsite', 'mail@mail.com');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usermail` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `usermail`, `password`, `first_name`, `last_name`) VALUES
(1, 'admin@admin.com', 'marusya', 'Barys', 'Marukhin'),
(2, 'mail@mail.com', 'marusya', 'Andrew', 'Lubochkin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
