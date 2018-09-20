<?php if ( ! defined('DIR_CONFIG')) exit('No direct script access allowed');

return [

	'database_type' => 'mysql', //тип базы данных

	'server' => '%HOSTNAME%', // Сервер базы данных стандарт: localhost

	'database_name' => '%DATABASE%', // Название базы данных

	'username' => '%USERNAME%', // Пользователь базы данных

	'password' => '%PASSWORD%', // Пароль от базы данных

	'charset' => 'utf8', // кодировка базы данных

	'port' => 3306 // Порт базы данных

];