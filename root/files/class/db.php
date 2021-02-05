<?php
////подключение к БД//
//$host_name = 'localhost';//хост бд s26.hostia.name
//$db_name   = 'jokgrom_morskie'; //имя бд e
//$user_name = 'jokgrom_morskie'; //пользователь бд e
//$password_db = '2nNMRP2U'; //пароль от бд 2nNMRP2U
//$charset_db = 'utf8';//кодировка для работы с бд
//
//$dsn = "mysql:host=$host_name;dbname=$db_name;";
////какая то хрень
//$opt = [
//    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
//    PDO::ATTR_EMULATE_PREPARES   => false,
//];
//$db = new PDO($dsn, $user_name, $password_db, $opt); //подключение к бд
//////подключение к БД////

//
$db = new PDO('mysql:host=localhost;dbname=jokgrom_morskie', 'mysql', 'mysql',
array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));