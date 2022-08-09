<?php

namespace App;

use App\Router;
use App\Request;

use App\Controllers\{
    AboutController,
    DashboardController,
    HomeController,
    PostController,
    LoginController,
    LogoutController,
    RegisterController,
};

class App {
    public function __construct()
    {
        //$this->autoloadClasses();
        $router = new Router;

        $router->get('/', [HomeController::class, 'index']);

        $router->get('/login', [LoginController::class, 'index']);
        $router->post('/login', [LoginController::class, 'create']);
        $router->get('/logout', [LogoutController::class, 'index']);

        $router->get('/register', [RegisterController::class, 'index']);

        $router->get('/dashboard', [DashboardController::class, 'index']);

        $router->get('/post/:id/:slug', [PostController::class, 'index']);
        $router->get('/post/create', [PostController::class, 'create']);
        $router->post('/post', [PostController::class, 'store']);
        $router->get('/post/edit/:id/:slug', [PostController::class, 'edit']);
        $router->put('/post/:id/:slug', [PostController::class, 'update']);

        $router->get('/about-us', [AboutController::class, 'index']);

        $request = new Request();
        $router->run($request);

        $requestedController = $router->getRequestedController();
        $requestedMethod = $router->getRequestedMethod();
        $params = $router->getParams();

        $request->setPageParams($params);

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
