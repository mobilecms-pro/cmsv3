<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine;

use System\Engine\Core;
/**
 * Класс главного контроллера
 * @author KpuTuK <bykputuk@ya.ru>
 */
abstract class Controller {
    /**
     *
     * @var \System\Engine\Core
     */
    protected $core;
    /**
     *
     * @var \System\Engine\Page\Page
     */
    protected $page;
    /**
     *
     * @var \System\Engine\Http\Request 
     */
    protected $request;
    /**
     *
     * @var \System\Engine\Model
     */
    protected $model;
    protected $user;
    protected $get = [];
    protected $post = [];
    protected $cookie = [];
    public function __construct(Core $core) {
        $this->core = $core;
        $this->request = $core->request;
        $this->page = $core->page;
        $this->get = $core->request->getQueryParams();
        $this->post = $core->request->getParsedBody();
        $this->cookie = $this->core->request->getCookieParams();
        if (isset($this->cookie['id'], $this->cookie['password'])){ 
            $this->user = new \Modules\Users\UserHelper(
                $this->model->getUserByAuth(
                    $this->cookie['id'], $this->cookie['password']
                )
            );
        } else {
            $this->user = new \Modules\Users\UserHelper(false);
        }
    }
    public function requestError() {
        echo $this->core->request->getUri()->getPath();
    }
    public function before() {
    }
    public function after() {
        
    }
    abstract public function actionIndex($params);
}
