<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace System\Engine\Routing;

/**
 * Description of RoutingEvent
 *
 * @author Олег
 */
class RoutingEvent
{
    /**
     *
     * @var string
     */
    protected $route;
    /**
     *
     * @var string
     */
    protected $controller;
    /**
     *
     * @var string
     */
    protected $action;
    public function setRoute(string $route)
    {
        $this->route = $route;
    }
    public function getRoute()
    {
        return $this->route;
    }
    public function setController(string $controller)
    {
        $this->controller = $controller;
    }
    public function getController()
    {
        return $this->controller;
    }
    public function setAction(string $action = 'actionIndex')
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
}
