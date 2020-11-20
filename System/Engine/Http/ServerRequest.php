<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use Psr\Http\Message\ServerRequestInterface;
/**
 * Обработчик сообщений запроса
 * @author KpuTuK
 */
abstract class ServerRequest extends Message implements ServerRequestInterface {
    /**
     * Атрибуты запроса
     * @var /ArrayObject 
     */
    protected $attributes;
    /**
     * COOKIE запроса
     * @var /ArrayObject
     */
    protected $cookies;
    /**
     * SERVER данные запроса
     * @var /ArrayObject
     */
    protected $server;
    /**
     * GET данные запроса
     * @var /ArrayObject
     */
    protected $query;
    /**
     * Загруженные файлы запроса
     * @var /ArrayObject
     */
    protected $files;
    /**
     * POST данные запроса
     * @var /ArrayObject
     */
    protected $parsedBody;
    /**
     * Создает экземпляр класса с указанными параметрами
     * @param mixed $uri
     * @param array $server
     * @param array $query
     * @param array $parsedBody
     * @param array $cookies
     * @param array $files
     */
    public function __construct(
        $uri, 
        array $server = [], 
        array $query = [], 
        array $parsedBody = [],
        array $cookies = [],
        array $files = [],
        array $attributes = []
    ) {
        parent::__construct($uri, $server);
        $this->server = new ParameterBag($server);
        $this->query = new ParameterBag($query);
        $this->parsedBody = new ParameterBag($parsedBody);
        $this->cookies = new ParameterBag($cookies);
        $this->files = new ParameterBag();
        $this->attributes = new ParameterBag($attributes);
        $this->files = new ParameterBag(
            $this->withUploadedFiles($files)->getUploadedFiles()
        );
    }
    /**
     * Возврашает содкржимое указанного ключа при наличии или значение по умолчанию
     * @param mixed $name
     * @param mixed $default
     * @return mixed
     */
    public function getAttribute($name, $default = null) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        } 
        return $default;
    }
    /**
     * Возвращает массив с атрибутами запроса
     * @return /ArrayObject|array
     */
    public function getAttributes() {
        return $this->attributes;
    }
    /**
     * Возвращает массив с COOKIES запроса
     * @return /ArrayObject|array
     */
    public function getCookieParams() {
        return $this->cookies;
    }
    /**
     * Возвращает массив с POST данными запроса
     * @return /ArrayObject|array
     */
    public function getParsedBody() {
        return $this->parsedBody;
    }
    /**
     * Возвращает массив с GET данными запроса
     * @return /ArrayObject|array
     */
    public function getQueryParams() {
        return $this->query;
    }
    /**
     * Возвращает переменные SERVER
     * @return /ArrayObject|array
     */
    public function getServerParams() {
        return $this->server;
    }
    /**
     * Вовращает загруженные файлы
     * @return /ArrayObject|array
     */
    public function getUploadedFiles() {
        return $this->files;
    }
    /**
     * Возвращает клон экземпляра класса с указанным именем и значенем аттрибута
     * @param string $name
     * @param mixed $value
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function withAttribute($name, $value) {
        $cloned = clone $this;
        $cloned->attributes[$name] = $value;
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса с указанными cookies
     * @param array $cookies
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function withCookieParams(array $cookies) {
        $cloned = $this;
        foreach ($cookies as $cookie) {
            if ($cookie instanceof CookieInterface) {
                $cloned->headers['Set-Cookie'][] = (string)$cookie;
                $cloned->cookies[$cookie->getName()] = $cookie->getValue();
            }
        }
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса с указанным(и) POST данным(и) запроса
     * @param mixed $data
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function withParsedBody($data) {
        $cloned = $this;
        if ( ! is_array($data)) {
            $data = [$data];
        }
        $cloned->parsedBody->exchangeArray($data);
        $this->attributes['__body__'] = $data;
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса с указанными GET данными запроса
     * @param array $query
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function withQueryParams(array $query) {
        $cloned = clone $this;
        $cloned->query->exchangeArray($query);
        $queryString = '';
        foreach ($query as $key => $value) {
            $queryString .= '&'.$key.'='.$value;
        }
        $this->uri = $this->uri->withQuery($queryString);
        return $this;
    }
    /**
     * Возвращает клон экземпляра класса с указанными файлами
     * @param array $uploadedFiles
     * @return \Psr\Http\Message\ServerRequestInterface
     * @throws \InvalidArgumentException
     */
    public function withUploadedFiles(array $uploadedFiles) {
        $cloned = $this; 
        foreach ($uploadedFiles as $file) {
            
            $cloned->files[] = new UploadedFile($file);
        }
        return $cloned;
    }
    /**
     * Возвращает клон экземпляра класса без указанного параметра
     * @param string $name
     * @return \Psr\Http\Message\ServerRequestInterface
     * @throws \InvalidArgumentException
     */
    public function withoutAttribute($name) {
        $clone = clone $this;
        if (isset($clone->attributes[$name])) {
            unset($clone->attributes[$name]);
            return $clone;
        }
        throw new \InvalidArgumentException(vsprintf(
            'Атрибут "%s" не сужествует', 
            $name
        ));
    }
}
