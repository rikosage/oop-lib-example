<?php

// Стандартный конфиг приложения
return [
    'jsonFile' => __DIR__ . "/../storage/database.json",
    'sqliteFile' => __DIR__ . "/../storage/database.sqlite3",
    'modules' => [
        'json' => true,
        'sqlite' => true,
        'mysql' => true,
    ],
];
