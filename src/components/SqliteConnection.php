<?php

namespace rikosage\components;

use ErrorException;
use rikosage\base\SQL;
use rikosage\interfaces\StorageInterface;

/**
 * Класс для работы с sqlite хранилищем
 */
class SqliteConnection extends SQL implements StorageInterface
{

    /**
     * Хранимое соединение с файлом
     * @var \SQLite3
     */
    private $_connection;

    /**
     * В конструкторе можно переопределить путь к файлу
     * @param string $filename Путь к файлу
     */
    public function __construct($filename)
    {
        $this->_connection = (new \SQLite3($filename));
        $this->prepare();
    }

    /**
     * Получить соединение. Наверняка пригодится.
     * @return \SQLite3
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @inheritDoc
     */
    public function prepare()
    {
        $this->_connection->exec($this->getDefaultPrepareQuery());
    }

    /**
     * @inheritDoc
     */
    public function insert(array $values, $into = null)
    {
        return $this->_connection->exec(
            $this->getInsertQuery($values, $into)
        );
    }

    /**
     * @inheritDoc
     */
    public function find($conditions = [], $limit = NULL){
        $limit = (int) $limit ?: static::$defaultLimit;

        $query = $this->getEqualSelectQuery($conditions) . " LIMIT $limit";

        $data = $this->_connection->query($query);

        if ($data === false) {
            throw new ErrorException(sprintf(
                "Invalid query: %s",
                $this->_connection->lastErrorMsg()
            ));
        }

        $result = [];
        while ($row = $data->fetchArray(SQLITE3_ASSOC)) {
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
