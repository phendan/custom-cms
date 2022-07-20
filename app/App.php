<?php

namespace App;

use App\Router;
use App\Request;

class App {
    public function __construct()
    {
        //$this->autoloadClasses();

        $router = new Router;

        $requestedController = $router->getRequestedController();
        $requestedMethod = $router->getRequestedMethod();
        $params = $router->getParams();

        $request = new Request($params);

        $controller = new $requestedController;
        $controller->{$requestedMethod}($request);
    }

    private function autoloadClasses()
    {
        spl_autoload_register(function ($namespace) {
            $projectNamespace = 'App\\';
            $className = str_replace($projectNamespace, '', $namespace);
            $filePath = '../app/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $className) . '.php';

            if (file_exists($filePath)) {
                require_once $filePath;
            }
        });
    }
}
