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
 * Description of Validator
 *
 * @author Олег
 */
class Validator
{
    public $valid = true;
    protected $error;
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function vilid($param)
    {
        
    }
}
