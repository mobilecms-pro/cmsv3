<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Fig\Http\Message\RequestMethodInterface;
use System\Engine\Http\RequestInterface as McRequestInterface;

/**
 * Обработчик запросов к HTTP серверу
 * @author KpuTuK
 */
class Request extends ServerRequest implements
    RequestInterface, RequestMethodInterface, McRequestInterface
{
    /**
     * Целевой запрос
     * @var string
     */
    protected $requestTraget = '/';
    /**
     * Иницилизирует класс с набором данных запроса
     * @param string $uri Запрашиваемый URI
     * @param string $method Метод запроса
     * @param array $server Массив имитирующий $_SERVER
     * @param array $query Массив имитирующий $_GET
     * @param array $parsedBody Массив имитирующий $_POST
     * @param array $cookies Массив имитирующий $_COOKIE
     * @param array $files Массив имитирующий $_FILES
     * @param array $attributes Аттрибуты запроса
     */
    public function __construct(
        $uri = '/',
        $method = Request::METH_GET,
        array $server = [], 
        array $query = [], 
        array $parsedBody = [], 
        array $cookies = [], 
        array $files = [],
        array $attributes = []
    ) {
        parent::__construct(
            $uri,
            $server,
            $query,
            $parsedBody,
            $cookies,
            $files,
            $attributes
        );
        $this->uri = $this->getUri()->withHost($server['HTTP_HOST']);
        $this->withMethod($method);
    }
    /**
     * Возвращает метод запроса
     * @return string
     */
    public function getMethod() {
        return $this->getServerParams()['HTTP_METHOD'];
    }
    /**
     * Возвращает целевой URI
     * @return string
     */
    public function getRequestTarget() {
        return $this->requestTraget;
    }
    /**
     * Возвращает обьект класса реализующего UriInterface
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri() {
        return $this->uri;
    }
    /**
     * Возвращает клон класса с указанным методом запроса
     * @param string $method
     * @return \Psr\Http\Message\RequestInterface
     */
    public function withMethod($method) {
        $cloned = $this;
        $cloned->server['HTTP_METHOD'] = $method;
        return $cloned;
    }
    /**
     * Возвращает клон класса с указанным целевым URI
     * @param string $requestTarget
     * @return \Psr\Http\Message\RequestInterface
     */
    public function withRequestTarget($requestTarget) {
        $cloned = $this;
        $cloned->requestTraget = $requestTarget;
        return $cloned;
    }
    /**
     * Возвращает клон класса с указанным URI 
     * @param \Psr\Http\Message\UriInterface $uri
     * @param bool $preserveHost 
     */
    public function withUri(UriInterface $uri, $preserveHost = false) {
        $cloned = $this;
        $cloned->uri = $uri;
        if (false === $preserveHost) {
            $cloned->withHeader('Host', $uri->getHost());
        }
        return $cloned;
    }
    /**
     * Является ли запрос ajax
     * @return boolean
     */
    public function isAjax() {
        if (isset($this->server['HTTP_X_REQUESTED_WITH']) && 
            (strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        ) {
            return true;
        }
        return false;
    }
    /**
     * 
     * @param type $url
     * @return type
     */
    public function withRedirect($url) {
        $this->headers['Location'] = $url;
        return clone $this;
    }
}
