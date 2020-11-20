<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Routing;

use System\Engine\Config;
/**
 * Обработчик путей
 * @author KpuTuK <bykputuk@ya.ru>
 */
class Router  {
    /**
     * Обьект Кеша роутов
     * @var \Application\Cache\ClassCache\RouteCacheMather
     */
    protected $mather;
    /**
     * Создает экземпляр класса с указанными настройками
     * @param System\Engine\Config\Config $config
     */
    public function __construct(Config $config) {
        if ( 
                ! file_exists(APPATH .'Cache/ClassCache/RouteCacheMather.php')
                || (\System\Engine\Core::DEBUG === true)
            ) {
            (new RouteDumper($config->routes))->dumpClass();
        }
        $this->mather = new \Application\Cache\ClassCache\RouteCacheMather();
    }
    /**
     * Обраюатывает путь и возвращает массив контроллера и параметров
     * @param string $uri
     * @param string $method
     * @return array
     */
    public function match(string $uri, string $method = 'GET') {
        return $this->mather->match($uri, $method);
    }
}
