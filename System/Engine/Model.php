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
 * Description of Model
 *
 * @author KpuTuK
 */
abstract class Model
{
    /**
     *
     * @var \System\Engine\MySQLiDB 
     */
    protected $mysqli = null;
    /**
     *
     * @var \System\Engine\Core
     */
    protected $core;
    /**
     * 
     */
    public function __construct()
    {
        $this->core = Core::getSingleton();
        $this->mysqli = Core::getSingleton()->mysqli;
    }
    /**
     * 
     * @param \mysqli_result $query
     * @param string $class
     * @return object
     */
    public function getColumn(\mysqli_result $query, string $class = 'stdClass')
    {
        return $query->fetch_object($class);
    }
    /**
     * 
     * @param \mysqli_result $query
     * @return void
     */
    public function free(\mysqli_result $query)
    {
        return $query->free();
    }
    public function getUserByAuth(int $id, string $password)
    {
        $user = $this->mysqli->query(
                'SELECT * FROM `#__users` WHERE `user_id` = "%s"  AND `password` = "%s"', 
            [$id, $password]);
        if ($user->num_rows !== 0) {
            return $user->fetch_object();
        }
        return false;
    }
}
