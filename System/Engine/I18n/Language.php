<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace System\Engine\I18n;

/**
 * Description of Language
 *
 * @author Олег
 */
class Language extends \ArrayObject
{
    public function __construct($lang)
    {
        parent::__construct(json_decode(
            file_get_contents(APPATH .'Configs/Languages/'. $lang .'.json'),
            true
        ));
    }
    public function offsetGet($index)
    {
        list($category, $name) = explode('.', $index);
        return parent::offsetGet($category)[$name];
    }
}
