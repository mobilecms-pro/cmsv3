<?php
return (new \System\Engine\Routing\RouteCollection())
->addRoute('home', '/', \Modules\Users\UsersController::class .'@actionIndex', [\System\Engine\Http\Request::METHOD_GET])
->addRoute('users.login', '/login', \Modules\Users\UsersController::class .'@login', [\System\Engine\Http\Request::METHOD_GET])
// base route
->addRoute('resolver.one', '(?<controller>[A-z0-9_\-]*)([\.A-z0-9]*)', \System\Engine\Routing\ControllerResolver::class.'@resolve')
->addRoute('resolver.two', '(?<controller>[A-z0-9_\-]*)/(?<action>[A-z0-9_\-]*)([\.A-z0-9]*)', \System\Engine\Routing\ControllerResolver::class.'@resolve')
->addRoute('resolver.three', '(?<module>[A-z0-9_\-]*)/(?<controller>[A-z0-9_\-]*)/(?<action>[A-z0-9_\-]*)([\.A-z0-9]*)', \System\Engine\Routing\ControllerResolver::class.'@resolve');
//end base route

