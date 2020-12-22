<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Routing;

use System\Engine\Core;
/**
 * Класс определителя роута
 * @author KpuTuK <bykputuk@ya.ru>
 */
class ControllerResolver
{
    /**
     * Обьект ядра системы
     * @var System\Engine\Core $core
     */
    private $core;
    /**
     *  Конструктор класса
     * @param System\Engine\Core $core
     */
    public function __construct(Core $core)
    {
        $this->core = $core;
    }
    /**
     * Определяет и проверяет на наличие файла класса роут
     * @param array $match Массив данных роута
     * @return string Строка роута вида класс@метод
     */
    public function resolve(array $match)
    {
        if (!array_key_exists('module', $match)) {
            $match['module'] = $match['controller'];
        }
        if (!array_key_exists('action', $match)) {
            $match['action'] = 'actionIndex';
        }
        $this->core->set('handle', $match);
        $module = ucfirst($match['module']);
        $controller = ucfirst($match['controller']).'Controller';
        $action = 'action'. ucfirst($match['action']);
        $class = 'Modules\\'. $module.'\\'. $controller;
        if ($this->checkRoute($controller, $module)) { 
            if (class_exists($class) && method_exists($class, $action)) {
            $controller = new $class($this->core);
            return $controller->$action($match);
            }
        }
        return $class .'@'. $action .' Not Found!';
    }
    /**
     * Проверяет наличие файла контроллера
     * @param string $controller
     * @param string $module
     * @return boolean
     */
    protected function checkRoute($controller, $module)
    {
        if (file_exists(ROOTPATH .'Modules/'. $module .'/'. $controller .'.php')) {
            return true;
        }
        return false;
    }
}
