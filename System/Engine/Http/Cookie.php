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
 * Description of Cookie
 *
 * @author KpuTuK
 */
class Cookie implements CookieInterface
{
    protected $domain;
    protected $path;
    protected $expire;
    protected $value;
    protected $name;
    public function __construct($name, $value = null, $expire = 0, $path = null, $domain = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
    }
    public function getName() {
        return $this->name;
    }
    public function getValue() {
        return $this->value;
    }
    public function __toString()
    {
        if ($this->expire !== 0) {
            $this->expire = gmdate('D, d-M-Y H:i:s T', time() + $this->expire);
        }
        if ($this->value === null) {
            $this->value = 'deleted';
            $this->expire = gmdate('D, d-M-Y H:i:s T', time() - 31536001);
        }
        return sprintf('%s=%s; expires=%s; path=%s; domain=%s; httponly', 
            $this->name,
            $this->value,
            $this->expire,
            $this->path,
            $this->domain
        );
    }

}
