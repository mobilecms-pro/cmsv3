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
 * Обьект пути
 * @author KpuTuK <bykputuk@ya.ru>
 */
class Route extends \ArrayObject {
    /**
     * Паттерны обработки путей
     * @var array
     */
    protected $patterns = [
        'i' => '[0-9]+',
        's' => '[a-zA-Z0-9\.\-_%]+'
    ];
    /**
     * Создает обьект роута с указанными параметрами
     * @param string $name Имя роута
     * @param string $pattern prce паттерн обработки
     * @param string $handler Обработчик роута вида класс@метод
     * @param array $methods Массив методов запроса роута
     * @param array $params Дополнительные параметры передаваемые в экшен
     */
    public function __construct(
        $name, 
        $pattern, 
        $handler, 
        array $methods = [],
        array $params = []) {
        parent::__construct([
            'name' => $name,
            'pattern' => $pattern,
            'handler' => $handler,
            'methods' => $methods,
            'params' => $params
        ]);
    }
    /**
     * @author Fabien Potencier <fabien@symfony.com>
     * Компилирует путь и возвращает массив из паттерна пути,
     *  параметров и флага компиляции
     * @return array
     */
    public function compile() {
        if (false !== strpos($this['pattern'], '<')) {
            return [
                'pattern' => $this['pattern'],
                'match' => true, 
                'params' => $this['params']
            ];
        }
        if (false === strpos($this['pattern'], '{')) {
            return [
                'pattern' => trim($this['pattern'], '/'),
                'match' => false, 
                'params' => $this['params']
            ];
        }
        $route = $this;
        return [ 
            'pattern' => trim(preg_replace_callback('#\{(\w+):(\w+)\}#', 
                function($match) use ($route) {
                    list(, $name, $prce) = $match;
                    return '(?<'.$name.'>'.strtr($prce, $route->patterns).')';
                }, $this['pattern']), 
            '/'),
            'match' => true,
            'params' => $this['params']
        ];
    }
    /**
     * Проверяет валидность индекса
     * @param string $index
     * @throws \InvalidArgumentException Исключение выбрасываемое
     *  в случае невалидности индекса
     */
    protected function validateIndex(string $index) {
        if ( ! in_array(
            $index, ['name', 'pattern', 'handler', 'methods', 'params']
        )) {
            throw new \InvalidArgumentException(sprintf(
                'Ожидался параметр "%s" вместо "%s"!', 
                implode(
                    '|', ['name', 'pattern', 'handler', 'methods', 'params']
                ),
                $index
            ));
        }
    }
    public function offsetSet($index, $newval) {
        $this->validateIndex($index);
        parent::offsetSet($index, $newval);
    }
    public function offsetExists($index) {
        $this->validateIndex($index);
        parent::offsetExists($index);
    }
    public function offsetGet($index) {
        $this->validateIndex($index);
        return parent::offsetGet($index);
    }
    public function offsetUnset($index) {
        $this->validateIndex($index);
        parent::offsetUnset($index);
    }
}
