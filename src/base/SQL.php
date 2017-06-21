<?php

namespace rikosage\base;

/**
 * Базовый класс SQL, собирающий общие запросы для SQLITE и MYSQL
 *
 * Абстракция в данном случае используется для того, чтобы нельзя было
 * создать экземпляр этого класса
 */
abstract class SQL
{
    /**
     * Синтаксис объявления автоинкремента. В SQLite и Mysql отличается
     * По умолчанию принимаем версию SQLite
     * @var string
     */
    protected static $autoincrement = "AUTOINCREMENT";

    /**
     * Максимальный размер выборки по умолчанию
     * @var integer
     */
    protected static $defaultLimit = 1000;

    /**
     * Генерирует строку запроса, подготавливающее БД.
     * Что-то типа миграции, только куда проще.
     * @param  string $tableName Имя таблицы.
     * @return string            Готовая строка запроса.
     */
    protected function getDefaultPrepareQuery($tableName = null)
    {
        // Мы точно знаем, что в наследнике есть константа TABLE_NAME,
        // поскольку она определяется интерфейсом
        if (!$tableName) {
            $tableName = static::TABLE_NAME;
        }

        return sprintf("
            CREATE TABLE IF NOT EXISTS %s (
                id INTEGER PRIMARY KEY %s NOT NULL,
                first_name VARCHAR(255),
                last_name VARCHAR(255),
                email VARCHAR(255)
            );",
            $tableName,
            static::$autoincrement
        );

    }

    /**
     * Формирует запроса на вставку. Нужно переписать, возможно,сделать отдельный билдер
     * @param  string $into   Таблица, в которую вставляем
     * @param  array  $values Массив значение вида `column => value`
     * @return string         Готовая строка запроса
     */
    protected function getInsertQuery(array $values, $into = null)
    {

        if (!$into) {
            $into = static::TABLE_NAME;
        }

        $query = "INSERT INTO $into (";
        $counter = 0;

        // Производим первый проход, вставляя в строку колонки
        foreach ($values as $column => $value) {
            $counter ++;
            $comma = $counter !== count($values);
            $query .= $column;
            $query .= $comma ? ", " : "";
        }

        // В середине готовимся к вставке значенией
        $query .= ") VALUES (";

        $counter = 0;

        // Производим второй прохож, заполняя значения.
        foreach ($values as $column => $value) {
            $counter ++;
            $comma = $counter !== count($values);
            $query .= "'$value'";
            $query .= $comma ? ", " : "";
        }

        return $query .= ");";
    }

    /**
     * Собираем стандартный select с равенством
     *
     * Первым параметром идет ассоциативный массив, где ключ равен названию поля,
     * а значение - значением в поле
     *
     * @param  array $where   Массив вида `field => value`
     * @param  string $fields На случай выборки конкретных полей
     * @param  string $from   Имя таблицы, из которой происходит выборка
     * @return string         Подготовленный SELECT запрос
     */
    protected function getEqualSelectQuery($where = [], $fields = "*", $from = NULL)
    {
        if (!$from) {
            $from = static::TABLE_NAME;
        }
        $query = "SELECT $fields FROM $from";

        if (!empty($where)) {
            $query .= " WHERE ";

            foreach ($where as $field => $value) {
                $query .= "$field = ";
                $query .= is_string($value) ? "\"$value\"" : $value;
            }
        }

        return $query;

    }
}
