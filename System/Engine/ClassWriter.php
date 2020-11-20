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
 * Создает класс кеша из отдельных файлов
 * @author KpuTuK <bykputuk@gmail.com>
 */
abstract class ClassWriter {
    /**
     * Расширение файлов
     * @var string 
     */
    protected $fileExtension;
    /**
     * Массив путей к файлам
     * @var array
     */
    protected $paths = [];
    /**
     * Массив имен файлов без расширения
     * @var array 
     */
    protected $names = [];
    /**
     * Содержание записанного кеша
     * @var string
     */
    protected $tmpString = '';
    /**
     * Создает экземпляр класса с указанными путями и расширением
     * @param array $paths Массив путей
     * @param string $fileExtension Расширение файлов
     */
    public function __construct(
        array $paths = [], string $fileExtension = '.php'
    ) {
        $this->paths = $paths;
        $this->fileExtension = $fileExtension;
    }
    /**
     * Создает дамп класса
     * @param string $class Имя класса кеша
     * @param string $method Имя кешируемого файла (без расширения)
     * @param array $methods Массив уже закешированых файлов
     * @param string|bool $extends Имя родительского класса
     */
    public function dumpClass(
        string $class, string $method, array $methods = [], string $extends = null
    ) {
        $this->names = $methods;
        $this->tmpString = "<?php
namespace Application\Cache\ClassCache;
// Created: ". (new \DateTime)->format('D, d M Y H:i:s') ."
class $class ". (($extends === null) ? '' : ' extends '. $extends) ."
{
    public function getNames() {
        return [
            {$this->generateNamesFiles($method)}
        ];
    }
    {$this->generateMethods()}
}
        ";
        $this->readable($class);
        file_put_contents(
            APPATH .'Cache/ClassCache/'. $class .'.php', $this->tmpString
        );
    }
    /**
     * Меняет права доступа к файлу
     * @param string $class
     */
    protected function readable(string $class) {
        $filename = APPATH .'Cache/ClassCache/'. $class .'.php';
        if (file_exists($filename) && !is_readable($filename)) {
            chmod(APPATH .'Cache/ClassCache/', 777);
            chmod($filename, 777);
        }
    }
    /**
     * Генерирует строку методов
     * @return string
     */
    protected function generateMethods() {
        $write = '';
        foreach ($this->names as $name) {
            $write .= $this->generateMethod($name);
        }
        return $write;
    }
    /**
     * Генерирует строку метода возвращающщего уже закешированые имена файлов
     * @param string $file
     * @return string
     */
    protected function generateNamesFiles(string $file) {
        if ( ! in_array($file, $this->names)) {
            $this->names[] = $file;
        }
        $write = '';
        foreach ($this->names as $name) {
            $write .= "\t\t'$name',\n";
        }
        return $write;
    }
    /**
     * Создает метод ввиде строки с указанным именем и содержанием указанного файла 
     * @param string $method  Имя загружаемого файла
     * @return string
     */
    protected function generateMethod(string $method) {
        return "
            public function  $method() {
                {$this->getContentMethod($method)}
            }
        ";
    }
    /**
     * Возвращает содержимое указанного файла
     * @param string $method Имя файла
     * @return string
     */
    protected function getContentMethod(string $method) {
        return file_get_contents($this->getFilePath($method));
    }
    /**
     * Проверяет наличие файла по имеющимся путям и возвращает его путь
     * @param string $file Имя файла
     * @return string
     * @throws \ErrorException Исключение бросаемое в случае отсутствия файла
     */
    protected function getFilePath(string $file) {
        foreach ($this->paths as $path) {
            if (file_exists($path .'/'. $file . $this->fileExtension)) {
                return $path .'/'. $file . $this->fileExtension;
            }
        }
        throw new \ErrorException(sprintf(
            'Файл "%s%s" не найден в папках "%s" !', 
            $file, $this->fileExtension, implode('|', $this->paths)
        ));
    }
    /**
     * Добавляет указанный путь в массив
     * @param string $path
     */
    public function withPath(string $path) {
        array_unshift($this->paths, $path);
    }
}

