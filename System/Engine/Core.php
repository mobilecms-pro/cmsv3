<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriInterface;
use Fig\Http\Message\RequestMethodInterface;

/**
 * Класс ядра движка
 * @author KpuTuK <bykputuk@ya.ru>
 */
class Core implements ContainerInterface
{
    const DEBUG = true;
    /**
     * Массив элементов контейнера
     * @var array
     */
    protected $container = [];
    /**
     * @var \System\Engine\Core
     */
    protected static $singleton;
    /**
     * @var \System\Engine\EventDispatcher
     */
    public $dispatcher;
    /**
     * @var \System\Engine\Routing\Router
     */
    public $routing;
    /**
     * @var \System\Engine\Http\Request 
     */
    public $request;
    /**
     * @var \System\Engine\Http\Response
     */
    public $response;
    /**
     * @var \System\Engine\MySQLiDB
     */
    public $mysqli;
     /**
     * @var \System\Engine\Config
     */
    public $config;
    /**
     * @var \System\Engine\Page\Page
     */
    public $page;
    /**
     * Создает экземпляр класса с указанными параметрами
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->container = new \ArrayObject($input);
        $this->dispatcher = new EventDispatcher();
        $this->config = new Config();
        $this->mysqli = new MySQLiDB($this->config);
        $this->routing = new Routing\Router($this->config);
        $this->request = new Http\Request(
            $_SERVER['REQUEST_URI'], 
            $_SERVER['REQUEST_METHOD'], 
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
        $this->page = new Page\Page($this);
        $this->response = new Http\Response($this->request->getUri());
        self::$singleton = $this;
    }
    /**
     * Реализация паттерна singleton
     * @return \System\Engine\Core
     */
    public static function getSingleton() {
        return self::$singleton;
    }
    /**
     * 
     * @param \Psr\Http\Message\UriInterface $uri
     * @param string $method
     */
    public function handle(UriInterface $uri, $method = RequestMethodInterface::METHOD_GET) 
    {
        $handle = $this->routing->match($uri->getPath(), $method);
        list($class, $action) = explode('@', $handle['handler']);
        if (class_exists($class) && method_exists($class, $action)) {
            $controller = new $class($this);
            $this->set('handle', $handle);
             return $controller->$action($handle['params']);
        }
        echo 'Not FOUND handle!';
    }
    /**
     * Загружает указанный файл настроек
     * @param string $name
     * @return mixed
     */
    public function loadConfig(string $name)
    {
        return $this->config->$name;
    }
    /**
     * Возвращает элемент контейнера по ключу
     * @param string $id
     */
    public function get($id) {
        $this->container->offsetGet($id);
    }
    /**
     * Проверяет наличие ключа в контейнере
     * @param string $id
     * @return bool
     */
    public function has($id) {
        return $this->container->offsetExists($id);
    }
    /**
     * Добавляет элемент в контейнер с указанным ключом
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function set(string $name, $value) {
        return $this->container->offsetSet($name, $value);
    }
}
