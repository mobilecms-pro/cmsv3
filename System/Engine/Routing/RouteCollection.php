<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Routing;

/**
 * Коллекция путей
 * @author KpuTuK <bykputuk@ya.ru>
 * @category Engine Route Component
 */
class RouteCollection extends \ArrayObject {
    /**
     * Создает экземпляр класса с указанной коллекцией путей
     * @param array $collection
     */
    public function __construct(array $collection = []) {
        parent::__construct($collection);
    }
    /**
     * Возвращает коллекцию ввиде массива
     * @return array
     */
    public function getCollection() {
        return $this->getArrayCopy();
    }
    /**
     * Добавляет путь в коллекцию
     * @param string $name Имя пути
     * @param string $pattern Паттерн пути
     * @param string $handler Обработчик пути
     * @param array $methods Методы доступа к пути
     * @return \System\Engine\Routing\RouteCollection
     */
    public function addRoute(
        string $name, string $pattern, string $handler, array $methods = []
    ) {
        $this[$name] = new Route($name, $pattern, $handler, $methods);
        return $this;
    }
    /**
     * Магический метод добавляющий обьект пути в массив
     * @param string $index Имя пути
     * @param \System\Engine\Routing\Route $newval Обьект пути
     * @throws \InvalidArgumentException Исключение выбрасываемое в случае
     *  если Обьект не наследует System\Engine\Routing\Route
     */
    public function offsetSet($index, $newval) {
        if ( ! $newval instanceof Route) {
            throw new \InvalidArgumentException(sprintf(
                'Роут "%s" должен реализовывать System\Engine\Routing\Route'
            ), $index);
        }
        parent::offsetSet($index, $newval);
    }
}
