<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use Psr\Http\Message\StreamInterface;
/**
 * Description of ObStream
 *
 * @author KpuTuK
 */
class ObStream implements StreamInterface
{
    protected $tmpBuff = [];
    /**
     * Конструктор класса
     * Включает буферизацию вывода
     * @link http://php.net/manual/ru/function.ob-start.php
     */
    public function __construct()
    {
        ob_start();
    }
    public function __toString()
    {
        return $this->getContents();
    }
    /**
     * Отправит содержимое самого верхнего буфера вывода (если оно имеется)
     * и отключит этот буфер вывода
     * @link http://php.net/manual/ru/function.ob-end-clean.php
     * @return string 
     */
    public function close()
    {
        return ob_end_clean();
    }
    public function detach()
    {
        throw new \RuntimeException;
    }

    public function eof()
    {
        return true;
    }
    
    /**
     * Возвращает содержимое текущего буфера и затем удаляет его.
     * @link http://php.net/manual/ru/function.ob-get-clean.php
     * @return string
     */
    public function getContents()
    {
        return ob_get_clean();
    }

    public function getMetadata($key = null)
    {
        if ($key !== null) {
            return ob_get_status(true)[$key];
        }
        return ob_get_status(true);
    }

    public function getSize()
    {
       return strlen($this->getContents());
    }

    public function isReadable()
    {
        return true;
    }

    public function isSeekable()
    {
        return false;
    }

    public function isWritable()
    {
        return true;
    }

    public function read($length)
    {
        return substr(ob_get_clean(), 0, $length);
    }

    public function rewind()
    {
        throw new \LogicException;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \LogicException;
    }

    public function tell()
    {
        return 0;
    }

    public function write($string)
    {
        echo $string;
    }
}
