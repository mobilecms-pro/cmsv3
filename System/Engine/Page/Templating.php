<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Page;

use System\Engine\ClassWriter;
use System\Engine\Http\ObStream;

/**
 * Description of Templating
 *
 * @author Олег
 */
class Templating extends ClassWriter
{

    /**
     * Обьект класса кеша
     * @var bool|\Application\Cache\ClassCache\TemplateCache
     */
    protected $cache = null;

    /**
     * Поток содержимого вывода
     * @var \Psr\Http\StreamStreamInterface
     */
    protected $stream = null;

    /**
     * Массив исключаемых шаблонов
     * @var array
     */
    protected $excluded = [];

    /**
     * Конструктор
     */
    public function __construct()
    {
        parent::__construct([APPATH . 'Themes/Default/'], '.tpl.php');
        if ($this->cache === null) {
            if (!file_exists(APPATH . 'Cache/ClassCache/TemplateCache.php')) {
                $this->dumpClass(
                    'TemplateCache',
                    'page',
                    [],
                    '\System\Engine\Page\CacheVars'
                );
            }
            $this->cache = new \Application\Cache\ClassCache\TemplateCache();
            $this->stream;
        }
    }

    /**
     * Принимает имя шаблона и массив данных к нему, если шаблон не исключен выводит его
     * @param string $template
     * @param array|mixed $vars
     * @return boolean|mixed
     */
    public function load(string $template, array $vars = [])
    {
        if (in_array($template, $this->excluded)) {
            return false;
        }
        foreach ($vars as $key => $value) {
            $this->cache->$key = $value;
        }
        if (!method_exists($this->cache, $template)) {
            $this->dumpClass(
                    'TemplateCache',
                    $template,
                    $this->cache->getNames(),
                    '\System\Engine\Page\CacheVars'
            );
        }
        $buff = new ObStream();
        $this->cache->$template();
        echo $buff->getContents();
    }

    /**
     * Добавляет переменную в шаблон
     * @param string $name
     * @param mixed $value
     */
    public function withVar(string $name, $value)
    {
        $this->cache->$name = $value;
    }

    public function exclude(string $template)
    {
        $this->excluded[] = $template;
    }

    /**
     * Возвращает содержимое файла
     * @param string $method Имя файла
     * @return string
     */
    protected function getContentMethod(string $method)
    {
        return "?>"
                . "<!-- start $method.tpl.php -->\n"
                . strtr(parent::getContentMethod($method), [
                    '$' => '$this->'
                ])
                . "<!-- end $method.tpl.php -->\n"
                . "<?php";
    }

    public function __destruct()
    {
        if (\System\Engine\Core::DEBUG) {
            $this->dumpClass(
                    'TemplateCache',
                    'page',
                    $this->cache->getNames(),
                    '\System\Engine\Page\CacheVars'
            );
        }
    }

}
