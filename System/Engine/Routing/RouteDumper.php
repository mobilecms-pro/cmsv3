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
 * Создает кеш путей
 * @author KpuTuK <bykputuk@ya.ru>
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RouteDumper 
{
    /**
     * Коллекция путей
     * @var \System\Engine\Routing\RouteCollection 
     */
    protected $collection;
    /**
     * Создает экземпляр класса с указанной коллекцией
     * @param \System\Engine\Routing\RouteCollection $collection
     */
    public function __construct(RouteCollection $collection = null) {
        $this->collection = $collection;
    }
    /**
     * Записывает кеш путей
     */
    public function dumpClass() {
        $time = (new \DateTime)->format('D, d M Y H:i:s');
        $read = <<<EOF
<?php
namespace Application\Cache\ClassCache;
/**
 * Create by RouteDumper {$time}
 * Кеш роутов
 */
class RouteCacheMather {
    public function getCachedRoutes() {
        return [
            {$this->generateNames()}
        ];
    }
    public function match(\$uri, \$method) {
        \$matches = [];
        \$pathInfo = trim(\$uri, '/');
        {$this->dumpRoutes()}
        return ['name' => 404, 'handler' => '\System\Engine\Controller@requestError', 'params' => [\$uri]];
    }
    protected function filterParams(\$params) {
        return array_filter(\$params, function (\$param) {
            return ! is_int(\$param);
        }, ARRAY_FILTER_USE_KEY);
    }  
}
EOF;
        file_put_contents(APPATH .'Cache/ClassCache/RouteCacheMather.php', $read);
    }
    /**
     * Генерирует строку методов
     * @return string
     */
    protected function generateNames() {
        $write = '';
        foreach (array_keys($this->collection->getCollection()) as $name) {
            $write .= "\t'$name',\n";
        }
        return $write;
    }
    /**
     * @todo Добавить сортировку по методам!!!!
     * 
     * @return 
     */
    protected function dumpRoutes() {
        $write = '';
        foreach ($this->collection->getCollection() as $route) {
            $compile = $route->compile();
            if (true === $compile['match']) {
                $write .= $this->dumpRouteMatcher(
                    $route['name'],
                    $compile['pattern'],
                    $route['handler'],
                    $route['methods'],
                    $route['params']
                );
                continue;
            }
            $write .= $this->dumpRoute(
                $route['name'],
                $compile['pattern'],
                $route['handler'],
                $route['methods'],
                $route['params']
            );
        }
        return $write;
    }
    /**
     * Генерирует содержимое метода на основе пути
     * @param string $name
     * @param string $pattern
     * @param string $handler
     * @param string|array $methods
     * @param array $params
     * @return string
     */
    public function dumpRoute(
        string $name, string $pattern, string $handler, $methods, array $params = []
    ) {
        if (is_array($methods)) {
            $methods = implode("', '", $methods);
        }
        if ( ! empty($methods)) {
return <<<EOF
        
\t/** Create for "{$name}" route **/
\tif (in_array(\$method, ['{$methods}'])) {
\t    if (\$pathInfo === '{$pattern}') {
\t        return ['name' => '{$name}', 'handler' => '{$handler}', 'params' => [{$this->createParams($params)}]];
\t    }
\t}

EOF;
        } 
return <<<EOF
        
\t/** Create for "{$name}" route **/
\tif (\$pathInfo === '{$pattern}') {
\t    return ['name' => '{$name}', 'handler' => '{$handler}', 'params' => [{$this->createParams($params)}]];
\t}

EOF;
    }
    protected function dumpRouteMatcher($name, $pattern, $handler, $methods, array $params = []) {
        if (is_array($methods)) {
            $methods = implode("', '", $methods);
        }
        if ( ! empty($methods)) {
return <<<EOF
        
\t/** Create for "{$name}" route **/
\tif (in_array(\$method, ['{$methods}'])) {
\t    if (preg_match('#^{$pattern}$#s', \$pathInfo, \$matches)) {
\t      return [
            'name' => '{$name}', 
            'handler' => '{$handler}', 
            'params' => array_merge(\$this->filterParams(\$matches), [{$this->createParams($params)}])
        ];
\t    }
\t}

EOF;
        } 
return <<<EOF
        
\t/** Create for "{$name}" route **/
\tif (preg_match('#^{$pattern}$#s', \$pathInfo, \$matches)) {
\t  return [
        'name' => '{$name}', 
        'handler' => '{$handler}', 
        'params' => array_merge(\$this->filterParams(\$matches), [{$this->createParams($params)}])
    ];
\t}

EOF;
    }
    protected function createParams(array $params = []) {
        $write = '';
        foreach ($params as $key => $value) {
            $write .= '\''.$key.'\' => \''.$value.'\', ';
}
        return $write;
    }
}
