<?php

namespace request;

use Exception;
use rikosage\interfaces\StorageInterface;

/**
 * Класс-обработчик запросов.
 * Может быть какой угодно, написан тут для примера взаимодействия с библиотекой
 */
class RequestCatcher
{

    /**
     * Прокси-свойство для доступа к глобальному массиву $_POST
     * @var array
     */
    private $_post = [];

    /**
     * Прокси-свойство для доступа к глобальному массиву $_GET
     * @var array
     */
    private $_get = [];

    /**
     * Результаты выполнения запросов будем хранить здесь
     * @var array
     */
    public $result = [
        'find' => [],
        'insert' => [],
    ];

    /**
     * Свойство для хранения библиотеки. Просто потому что так удобнее
     * @var \rikosage\RikosageApplication
     */
    protected $app;

    /**
     * Первичная инициализация компонента
     * @param \rikosage\RikosageApplication $app Инстанс библиотеки
     */
    public function __construct($app)
    {
        // Данные никак не обрабатываем
        $this->_post = $_POST;
        $this->_get = $_GET;
        $this->app = $app;

        // Продолжаем инициализацию
        $this->run();
    }

    /**
     * Определяем поведение при получении GET запроса
     * @throws Exception В случае, если переданный в GET метод не существует
     * @return void
     */
    private function run()
    {
        // Если передали экшен - отрабатываем. Иначе игнорируем дальшейшее выполнение
        if (!empty($this->_get) && $this->_get['action']) {

            // Имя метода будет собираться из GET параметра action
            $method = "action" . ucfirst($this->_get['action']);

            // Зовем экшен
            if (method_exists($this, $method)) {
                call_user_func([$this, $method]);
            } else {
                throw new Exception("Unexpected method $method");
            }
        }
    }

    /**
     * Получить параметры GET-запроса
     * @param  string $item Запрос конкретного элемента GET
     * @return mixed        Элемент массива или целиком GET
     */
    public function getRequest($item = NULL)
    {
        if ($item) {
            return isset($this->_get[$item]) ? $this->_get[$item] : NULL;
        }

        return $this->_get;
    }

    /**
     * Получить параметры POST-запроса
     * @param  string $item Запрос конкретного элемента POST
     * @return mixed        Элемент массива или целиком POST
     */
    public function getPost($item = null)
    {
        if ($item) {
            return isset($this->_post[$item]) ? $this->_post[$item] : NULL;
        }
        return $this->_post;
    }

    /**
     * Устанавливает ошибку в куки
     * @param string $message Текст ошибки
     */
    private function setError($message)
    {
        setcookie("error", $message, time()+1);
    }

    /**
     * Получить ошибку, установленную ранее
     * @return string   Строка с описанием ошибки, или NULL, если нету
     */
    public function getError()
    {
        return isset($_COOKIE['error']) ? $_COOKIE['error'] : NULL;
    }

    /**
     * Редирект на главную
     * @return void
     */
    private function goHome()
    {
        header("Location: /");
    }

    /**
     * Экшен для обработки поиска.
     *
     * Автор: Можно было бы запилить отдельный контроллер в лучших традициях MVC,
     * но зачем? Приложение ведь тестовое, проверяется только библиотека
     * @return void
     */
    public function actionFind()
    {
        if (!$this->getPost("storage")) {
            $this->setError("Storage can't be blank!");
            $this->goHome();
        }
        $action = $this->getRequest("action");

        foreach($this->getPost("storage") as $connection => $val){
            if ($this->app->$connection instanceof StorageInterface) {
                if ($this->getPost("email")) {
                    $this->result[$action][$connection] = $this->app->$connection->findByEmail($this->getPost("email"));
                } else {
                    $this->result[$action][$connection] = $this->app->$connection->find();
                }
            }
        }
    }

    /**
     * Экшен для обработки вставки в хранилища
     * @return void
     */
    public function actionInsert()
    {
        if (!$this->getPost("storage")) {
            $this->setError("Storage can't be blank!");
            $this->goHome();
        }
        if (!$this->getPost($this->getRequest("action"))) {
            $this->setError("Data is empty!");
            $this->goHome();
        }

        $action = $this->getRequest("action");
        $data = $this->getPost($action);


        foreach ($this->getPost("storage") as $connection => $val) {
            if ($this->app->$connection instanceof StorageInterface) {
                $this->result[$action] = $this->app->$connection->insert($data);
            }
        }

    }
}
