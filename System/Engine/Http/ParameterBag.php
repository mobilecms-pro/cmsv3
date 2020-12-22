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
 * Description of ParametrBag
 *
 * @author KpuTuK
 */
class ParameterBag extends \ArrayObject
{
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            return null;
        }
        return htmlspecialchars(trim(parent::offsetGet($index)));
    }
    public function merge(array $array = [])
    {
        return $this->exchangeArray(array_merge($this->getArrayCopy(), $array));
    }
}
