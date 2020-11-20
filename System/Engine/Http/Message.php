<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Обработчик сообщений запроса
 * @author KpuTuK
 */
class Message implements MessageInterface {
    /**
     * Версия протокола
     * @var string 
     */
    protected $protocol = '1.0';
    /**
     * Массив заголовков
     * @var array
     */
    protected $headers = [];
    /**
     * Тело сообщения
     * @var \Psr\Http\Message\StreamInterface 
     */
    protected $body = null;
    /**
     * Обработчик uri
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;
    /**
     * Иницилизирует класс с набором заголовков из $_SERVER
     * @param mixed $uri
     */
    public function __construct($uri) {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        }
         $this->uri = $uri;
    }
    /**
     * Возвращает тело сообщения
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody() {
        return $this->body;
    }
    /**
     * Возвращает все значения указанного заголовка сообщения
     * @param string $name Имя заголовка без учета регистра
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getHeader($name) {
        $headerName = ucfirst($name);
        if ($this->hasHeader($headerName)) {
            return $this->headers[$headerName];
        }
        return null;
    }
    /**
     * Возвращает указанный заголовок в виде сторки
     * @param string $name Имя заголовка без учета регистра
     * @return string Заголовок в виде сторки
     * @throws \InvalidArgumentException
     */
    public function getHeaderLine($name) {
        $headerName = ucfirst($name);
        if ($this->hasHeader($headerName)) {
            if (is_string($this->headers[$headerName])) {
                return (string)$headerName.': '.$this->headers[$headerName];
            }
            return $headerName.': '.
            implode(', ', $this->headers[$headerName]);
        }
        return null;
    }
    /**
     * Возвращает все значения заголовков сообщения
     * @return array Ассоциативный массив заголовков
     */
    public function getHeaders() {
        return $this->headers;
    }
    /**
     * Возвращает версию протокола ввиде строки
     * @return string Версия протокола
     */
    public function getProtocolVersion() {
        return $this->protocol;
    }
    /**
     * Проверяет наличие указанного заголовка
     * @param string $name Имя заголовка без учета регистра
     * @return bool True если заголовок присутствует или false если отсутствует
     */
    public function hasHeader($name) {
        return array_key_exists($name, $this->headers);
    }
    /**
     * Возвращает клон экземпляра класса с заменой значения указанного заголовка
     * @param string $name Имя заголовка без учета регистра
     * @param array|string $value Значение указанного заголовка
     * @return \Psr\Http\Message\MessageInterface
     * @throws \InvalidArgumentException
     */
    public function withAddedHeader($name, $value) {
        $headerName = ucfirst($name);
        $cloned = clone $this;
        if ($cloned->hasHeader($headerName)) {
            $cloned->headers[$headerName] = $value;
            unset($cloned->headers[$headerName]);
            return $cloned;
        }
        $this->headerNotExists($headerName);
    }
    /**
     * Возвращает клон экземпляра класса с указанным телом сообщения
     * @param \Psr\Http\Message\StreamInterface $body Тело сообщения
     * @return \Psr\Http\Message\MessageInterface
     */
    public function withBody(StreamInterface $body) {
        $cloned = clone $this;
        $cloned->body = $body;
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса с заменой указанного заголовка
     * @param string $name Имя заголовка без учета регистра
     * @param string $value Содержимое заголовка
     * @return \Psr\Http\Message\MessageInterface
     */
    public function withHeader($name, $value) {
        $cloned = clone $this;
        $cloned->headers[ucfirst($name)] = $value;
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса с заменой указанного протокола
     * @param string $version версия HTTP протокола
     * @return \Psr\Http\Message\MessageInterface
     */
    public function withProtocolVersion($version) {
        $cloned = clone $this;
        $cloned->protocol = (string)$version;
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса без указанного заголовка
     * @param string $name Имя заголовка без учета регистра
     * @return \Psr\Http\Message\MessageInterface
     * @throws \InvalidArgumentException
     */
    public function withoutHeader($name) {
        $headerName = ucfirst($name);
        $cloned = clone $this;
        if ($cloned->hasHeader($headerName)) {
            unset($cloned->headers[$headerName]);
            return $cloned;
        }
        $this->headerNotExists($headerName);
    }
    protected function headerNotExists($name) {
        throw new \InvalidArgumentException(vsprintf(
            'Заголовок "%s" не найден! ', $name
        ));
    }
}

