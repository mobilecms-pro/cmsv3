<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use Psr\Http\Message\ResponseInterface;
/**
 * Обработчик ответов HTTP сервера
 * @author KpuTuK
 */
class Response extends Message implements ResponseInterface {
    /**
     * Статус состояния согласно коду
     * @var string
     */
    protected $reasonPrase = 'OK';
    /**
     * Код статуса запроса
     * @var int
     */
    protected $status = 200;
    /**
     * Ассоциативный массив кодов и присоенных им статусов состояния
     * @author Fabien Potencier <fabien@symfony.com>
     * @var array 
     */
    protected $statuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];
    /**
     * Иницилизирует класс класс с указанным кодом и статусом
     * @param int $code
     * @param string $reasonPhrase
     */
    public function __construct(
        $uri = null,
        int $code = 200,
        string $reasonPhrase = '',
        array $headers = []
    ) {
        parent::__construct($uri);
        $this->headers = $headers;
        if ( !in_array((int)$code, $this->statuses)) {
            $code = 500;
        }
        $this->status = $code;
        if ((string)$reasonPhrase === '') {
            $reasonPhrase = $this->statuses[$code];
        }
        $this->reasonPrase = $reasonPhrase;
    }
    
    public function __toString()
    {
        foreach ($this->getHeaders() as $name => $value) {
            $this->createHeader($name, $value);
        }
       return $this->getBody()->detach();
    }
    /**
     * Возвращает статус состояния
     * @return string
     */
    public function getReasonPhrase() {
        return $this->reasonPrase;
    }
    /**
     * Возвращает код состояния
     * @return int
     */
    public function getStatusCode() {
        return $this->status;
    }
    /**
     * Возвращает клон класса с указанным кодом и статусом
     * @param int $code
     * @param string $reasonPhrase
     * @return Psr\Http\Message\ResponseInterface
     * @throws \InvalidArgumentException
     */
    public function withStatus($code, $reasonPhrase = '') {
        if ( ! array_key_exists((int)$code, $this->statuses)) {
            throw new \InvalidArgumentException(
                'Неверный код состояния сервера!'
            );
        }
        $cloned = clone $this;
        $cloned->status = $code;
        if ($reasonPhrase === '') {
            $reasonPhrase = $this->statuses[$code];
        }
        $cloned->reasonPrase = (string)$reasonPhrase;
        return $cloned;
    }
    protected function createHeader($name, $values)
    {
        if (is_array($values)) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
            return;
        }
        header(sprintf('%s: %s', $name, $values), false);
    }
}