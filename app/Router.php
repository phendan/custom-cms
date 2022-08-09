<?php

namespace App;

use App\Request;
use App\Controllers\NotFoundController;

class Router {
    private array $routes = [];
    private string $controller = NotFoundController::class;
    private string $method = 'index';
    private array $params = [];

    public function run(Request $request)
    {
        $requestUrl = $this->parseUrl();
        $requestMethod = $request->getMethod();

        foreach ($this->routes[$requestMethod] as $url => $response) {
            [$controller, $method] = $response;
            if (!class_exists($controller) || !method_exists($controller, $method)) continue;

            $urlSegments = explode('/:', $url);
            $page = array_shift($urlSegments);
            $paramNames = $urlSegments;

            // No params, check for exact matches
            if (count($paramNames) === 0) {
                if ($page !== $requestUrl) continue;

                $this->controller = $controller;
                $this->method = $method;

                return;
            }

            if (!str_starts_with($requestUrl, $page)) continue;

            $params = explode('/', str_replace($page . '/', '', $requestUrl));

            // If there are fewer/more params than were specified for the route
            if (count($paramNames) !== count($params)) continue;

            $this->controller = $controller;
            $this->method = $method;
            $this->params = array_combine($paramNames, $params);
        }
    }

    private function parseUrl(): string
    {
        if (!isset($_GET['url'])) {
            return '/';
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return '/' . $url;
    }

    public function get(string $route, array $response)
    {
        $this->routes['GET'][$route] = $response;
    }

    public function post(string $route, array $response)
    {
        $this->routes['POST'][$route] = $response;
    }

    public function put(string $route, array $response)
    {
        $this->routes['PUT'][$route] = $response;
    }

    public function patch(string $route, array $response)
    {
        $this->routes['PATCH'][$route] = $response;
    }

    public function delete(string $route, array $response)
    {
        $this->routes['DELETE'][$route] = $response;
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
