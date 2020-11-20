<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine;

/**
 * Загрузчик настроек кеша
 * @author KpuTuK <bykputuk@ya.ru>
 */
class Config extends ClassWriter {
    /**
     * Обьект класса кеша
     * @var bool|\Application\Cache\ClassCache\ConfigCache
     */
    protected $cache = null;
    /**
     * полный путь текущего файла настроек
     * @var string
     */
    protected $loadFile = null;
    /**
     * Конструктор
     */
    public function __construct() {
        parent::__construct([APPATH .'Configs/']);
        if ($this->cache === null) {
            if ( ! file_exists(APPATH .'Cache/ClassCache/ConfigCache.php')) {
                $this->dumpClass('ConfigCache', 'database');
            }
            $this->cache = new \Application\Cache\ClassCache\ConfigCache();
            if (\System\Engine\Core::DEBUG) {
                $this->dumpClass(
                    'ConfigCache',
                    'database',
                    $this->cache->getNames()
                );
            }
        }
    }
    /**
     * Возвращает массив настроек по имени файла настроек
     * @param string $method
     * @return array
     */
    public function __get(string $method) {
        if ( ! method_exists($this->cache, $method)) {
            $this->dumpClass('ConfigCache', $method, $this->cache->getNames());
            return include $this->loadFile;
        }
        return $this->cache->$method();
    }
    /**
     * Проверяет наличие файла по имеющимся путям и возвращает его путь
     * @param string $file Имя файла
     * @return string
     * @throws \ErrorException Исключение бросаемое в случае отсутствия файла
     */
    protected function getFilePath(string $file): string
    {
        return $this->loadFile = parent::getFilePath($file);
    }
    /**
     * Возвращает содержимое указанного файла
     * @param string $method Имя файла
     * @return string
     */
    protected function getContentMethod(string $method) {
        return str_replace('<?php', '', parent::getContentMethod($method));
    }
}

