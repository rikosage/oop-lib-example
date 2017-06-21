<?php

namespace rikosage\components;

use mysqli;

use ErrorException;
use rikosage\base\SQL;
use rikosage\interfaces\StorageInterface;

/**
 * Класс для работы с Mysql-хранилищем. В отличии от остальных хранилищ,
 * этот класс имеет смысл реализовать с использованием (анти)паттерна Singleton,
 * поскольку больше одного соединения нам не требуется.
 */
class MysqlConnection extends SQL implements StorageInterface
{

    /**
     * Переопределяем переменную
     * на корректный тип объявления автоинкремента
     * @var string
     */
    protected static $autoincrement = "AUTO_INCREMENT";

    /**
     * Хранит в себе инстанс класса
     * @var self
     */
    private static $_instance;

    /**
     * Хранит соединение
     * @var mysqli
     */
    private $connection;

    /**
     * Запрещаем создавать новые объекты класса
     */
    private function __construct(){}

    /**
     * Запрещаем клонировать объект
     */
    private function __clone(){}

    /**
     * Получить инстанс класса, параллельно создав подключение, если это требуется
     * @param  array $config  Настройки соединения
     * @throws \Exception     Если каким-то чудом получили пустой конфиг - бросаем внятное исключение
     * @return self
     */
    public static function getInstance($config = null)
    {
        if (!$config) {
            throw new \Exception("Mysql config is required!");
        }

        if (!self::$_instance) {
            self::$_instance = new static;
        }

        if (!self::$_instance->connection) {
            self::$_instance->connection = new mysqli($config['dns'],$config['username'],$config['password'],$config['db']);
        }

        self::$_instance->prepare();

        return self::$_instance;
    }

    /**
     * @inherirDoc
     */
    public function prepare()
    {
        self::$_instance->connection->query(
            $this->getDefaultPrepareQuery()
        );
    }

    /**
     * Вставить данные в таблицу
     * @param  array  $values Массив значение вида `column => value`
     * @param  string $into   Таблица, в которую вставляем
     * @return bool           TRUE в случае успеха
     */
    public function insert(array $values, $into = null)
    {
        return self::$_instance->connection->query(
            $this->getInsertQuery($values, $into)
        );
    }

    /**
     * @inheritDoc
     */
    public function find($conditions = [], $limit = NULL)
    {
        $limit = (int) $limit ?: static::$defaultLimit;
        $query = $this->getEqualSelectQuery($conditions) . " LIMIT $limit";

        $data = self::$_instance->connection->query($query);

        if ($data === false) {
            throw new ErrorException(sprintf(
                "Invalid query: %s",
                self::$_instance->connection->error_list[0]['error']
            ));
        }

        $result = [];

        while ($row = $data->fetch_assoc()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail($email){
        return $this->find(['email' => $email], 1);
    }

}
