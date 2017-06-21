<?php

namespace rikosage\components;

use rikosage\interfaces\StorageInterface;

/**
 * Класс для работы с JSON-хранилищем
 */
class JsonConnection implements StorageInterface
{

    /**
     * Путь до файла хранилища
     * @var string
     */
    public $file;

    /**
     * Это свойство хранит в себе актуальную информацию о хранилище
     * @var string
     */
    protected $data;

    /**
     * Максимальный размер выборки по умолчанию
     * @var integer
     */
    protected static $defaultLimit = 1000;

    /**
     * Можно переопределить файл в конструкторе
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->prepare();
    }

    /**
     * @inheritDoc
     */
    public function prepare(){
        if (!file_exists($this->file)) {
            touch($this->file);

            $defaultStructure = [
                static::TABLE_NAME => [],
            ];

            file_put_contents($this->file, json_encode($defaultStructure));
        }

        $this->data = json_decode(file_get_contents($this->file), true);
    }

    /**
     * @inheritDoc
     */
    public function insert(array $values, $into = NULL)
    {
        $insert = [];
        $insert['id'] = $this->getNextId();

        foreach ($values as $key => $value) {
            $insert[$key] = $value;
        }

        $this->data[static::TABLE_NAME][] = $insert;

        return $this->save();
    }

    /**
     * Получить следующий Id, который должен быть вставлен в файл
     * @return int
     */
    private function getNextId()
    {
        $lastItem = end($this->data[static::TABLE_NAME]);
        return isset($lastItem['id']) ?  ++$lastItem['id'] : 1;
    }

    /**
     * Сохраняет данные в json-файл после вставки в массив
     * @return bool TRUE в случае успеха
     */
    private function save()
    {
        return (bool)file_put_contents($this->file, json_encode($this->data));
    }

    /**
     * @inheritDoc
     */
    public function find($conditions = [], $limit = NULL)
    {
        $limit = (int) $limit ?: static::$defaultLimit;

        $result = [];
        // Пробегаем столько раз, сколько указано в $limit
        for ($i=0; $i < count($this->data[static::TABLE_NAME]); $i++) {

            $matches = 0;

            // Пробегаем по условиям, если они есть, записываем только при совпадении
            if (!empty($conditions)) {
                foreach ($conditions as $key => $value) {
                    if ($this->data[static::TABLE_NAME][$i][$key] === trim($value)) {
                        $result[] = $this->data[static::TABLE_NAME][$i];
                        // Прерываем, если найдено достаточно записей
                        $matches++;
                        if ($matches >= $limit) {
                            break;
                        }
                    }
                }
            // Если условий нет - пишем подряд
            } else {
                $result[] = $this->data[static::TABLE_NAME][$i];
            }
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function findByEmail($email)
    {
        return $this->find(['email' => $email], 1);
    }
}
