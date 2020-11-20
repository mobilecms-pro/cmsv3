<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine\Page;

use System\Engine\Core;
use System\Engine\Http\Request;
use System\Engine\Http\ObStream;
/**
 * Description of Page
 *
 * @author Олег
 */
class Page extends Templating
{

    protected $core;
    /**
     * 
     * @var \System\Engine\Http\ObStream
     */
    protected $stream;
    public function __construct(Core $core)
    {
        $this->core = $core;
        parent::__construct();
        $this->stream = new ObStream();
    }
    /**
     * 
     * @param type $url
     * @param type $method
     * @param type $upload
     * @return \System\Engine\Page\FormsBuilder
     */
    public function form($url = '?', $method = Request::METHOD_POST, $upload = false)
    {
        return new FormsBuilder($this, $url, $method, $upload);
    }
    public function __destruct()
    {
        $this->load('page', ['article' => $this->stream->getContents()]);;
        $this->core->response->withBody($this->stream);
        parent::__destruct();
    }
}
