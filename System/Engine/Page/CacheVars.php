<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Page;

/**
 * Description of CacheVars
 *
 * @author Олег
 */
class CacheVars extends \ArrayObject
{
    public function __construct()
    {
        parent::__construct([], \ArrayObject::ARRAY_AS_PROPS);
    }
}
