<?php

/**
 * Composer используется исключительно для неймспейсов
 * Без них грустно, и никому не нужно
 * Автолоадер вручную писать долго и муторно
 *
 * Кстати, я не ошалел от собственной значимости. Все именования классов,
 * содержащие мой ник, служат сугубо для того, чтобы никак не пересекаться
 * с другими проектами и названиями.
 */
namespace rikosage;

use rikosage\components\SqliteConnection;
use rikosage\components\MysqlConnection;
use rikosage\components\JsonConnection;

/**
 * Класс приложения библиотеки.
 * В конструкторе можно передать конфиг для переопределения
 */
class RikosageApplication
{
    /**
     * Настройки приложения
     * @var object
     */
    public $config;

    /**
     * Класс для работы с Sqlite
     * @var SqliteConnection
     */
    public $sqlite;

    /**
     * Класс для работы с mysql-базой
     * @var MysqlConnection
     */
    public $mysql;

    /**
     * Класс для работы с файлом json
     * @var JsonConnection
     */
    public $json;

    /**
     * Инициализация приложения.
     *
     * Можно передать любые настройки, если вдруг от этого класса
     * происходит наследование
     * @param array $config
     */
    public function __construct($config = null)
    {
        $defaultConfig = require(__DIR__ . "/config/default.php");

        // Совмещаем стандартные параметры с кастомными, либо
        // используем стандартные
        $this->config = $config
            ? (object) array_merge($defaultConfig, $config)
            : (object) $defaultConfig;

        $this->setConnections();
    }

    /**
     * Устанавливает соединения и создает инстансы классов для работы с разными
     * типами хранилищ
     */
    public function setConnections()
    {
        if (
            $this->config->modules['mysql']
            && isset($this->config->mysqlConnection)
        ) {
            $this->mysql = MysqlConnection::getInstance($this->config->mysqlConnection);
        }

        if ($this->config->modules['sqlite']) {
            $this->sqlite = new SqliteConnection($this->config->sqliteFile);
        }

        if ($this->config->modules['json']) {
            $this->json = new JsonConnection($this->config->jsonFile);
        }
    }
}
