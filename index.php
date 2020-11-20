<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

use System\Engine\ClassLoader;
use System\Engine\Core;

error_reporting(-1);
ini_set('display_errors', true);

$timeStart = microtime(true);
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('APPATH', ROOTPATH .'Application'. DIRECTORY_SEPARATOR);

#boots Данный код можно удалить

#boote

include_once ROOTPATH .'/System/Engine/ClassLoader.php';
$loader = new ClassLoader(ROOTPATH);
$loader->withPath('Psr\\', '\System\Library\Psr\\');
$loader->withPath('Fig\\', '\System\Library\Fig\\');
$loader->register();

$core = new Core(['loader' => $loader]);
$core->handle($core->request->getUri());

echo $timeStart - microtime(true);