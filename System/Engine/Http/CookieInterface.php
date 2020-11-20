<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

/**
 *
 * @author KpuTuK
 */
interface CookieInterface
{
    public function __construct($name, $value = null, $expire = 0, $path = '/', $domain = null);
    /**
     * Возвращает строку Cookie
     * @return string
     */
    public function __toString(): string;
}
