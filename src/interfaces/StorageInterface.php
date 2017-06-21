<?php

namespace rikosage\interfaces;

/**
 * В этом интерфейсе можно объявить методы, которые будут иметь одинаковую
 * сигнатуру в абсолютно разных соединениях и хранилищах.
 */
interface StorageInterface
{

    /**
     * Стандартное имя таблицы или ключа массива
     * @var string
     */
    const TABLE_NAME = "users";
    /**
     * Получить запись по Email пользователя
     * @param  string $email Email пользователя
     * @return mixed         Массив выборки из хранилища
     */
    public function findByEmail($email);

    /**
     * Вставить данные в таблицу или файл
     * @param  array  $values Массив значение вида `column => value`
     * @param  string $into   Таблица или ключ массива, куда вставляем
     * @return bool           TRUE в случае успеха
     */
    public function insert(array $values, $into = NULL);

    /**
     * Готовит схему или файл для дальшейшего использования
     * @return void
     */
    public function prepare();

    /**
     * Осуществить поиск в хранилище
     *
     * Первым параметром передаются условия выборки вида field => value,
     * где field - поле в таблице или хранилище, а value - значение, с
     * которым требуется сравнение
     * @param  array     $conditions  Массив вида `field => value`
     * @param  integer   $limit       Максимальный размер выборки
     * @return array                  Список найденных полей в виде массива
     */
    public function find($conditions = [], $limit = NULL);
}
