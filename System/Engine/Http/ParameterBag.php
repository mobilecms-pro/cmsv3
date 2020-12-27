<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Http;

use System\Engine\Validator;

/**
 * Корзина параметров
 * @author KpuTuK
 */
class ParameterBag extends \ArrayObject
{
    /**
     * Возвращает отфильтрованое значение согласно ключу
     * @see https://www.php.net/manual/ru/arrayaccess.offsetget
     * @param string $index ключ
     * @return mixed значение по ключу
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            return null;
        }
        return htmlspecialchars(trim(parent::offsetGet($index)));
    }
    /**
     * Добавляет массив в корзину
     * @param array $array Добавляемый массив
     * @return array Массив корзины
     */
    public function merge(array $array = [])
    {
        return $this->exchangeArray(array_merge($this->getArrayCopy(), $array));
    }
    /**
     * Возвращает обьект валидатора с массивом данных корзины
     * @return System\Engine\Validator
     */
    public function getValidator()
    {
        return new Validator($this->getArrayCopy());
    }
}
