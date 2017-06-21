<?php

// Просто привычка для отладки на локалке
if (file_exists(__DIR__ . "/dev.php")) {
    require(__DIR__ . "/dev.php");
}
if (defined("DEV") && constant("DEV") === true) {
    error_reporting(E_ALL);
}

// Подключаем автолоадер
require_once(__DIR__ . "/vendor/autoload.php");

// Включаем приложение для работы с библиотекой
require_once(__DIR__ . "/RequestCatcher.php");

// Инициализируем библиотеку
$app = (new \rikosage\RikosageApplication([
    'mysqlConnection' => [
        'dns' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'db' => 'new_edge',
    ],
]));

// Создаем экземпляр приложения
$request = new \request\RequestCatcher($app);

// Сразу рендерим вьюху
require(__DIR__ . "/view.php");
