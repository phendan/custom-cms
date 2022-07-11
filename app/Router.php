<?php

class Router {
    private string $controller = 'HomeController';
    private string $method = 'index';
    private array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        $requestedController = ucfirst(strtolower($url[0] ?? '')) . 'Controller';
        $controllerPath = "../app/Controllers/{$requestedController}.php";

        if ($url && file_exists($controllerPath)) {
            require_once $controllerPath;
            $this->controller = $requestedController;
            unset($url[0]);
        } else {
            require_once "../app/Controllers/{$this->controller}.php";
        }

        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = array_values($url);
    }

    private function parseUrl(): array
    {
        if (!isset($_GET['url'])) {
            return [];
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        return $url;
    }

    public function getRequestedController(): string
    {
        return $this->controller;
    }

    public function getRequestedMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
