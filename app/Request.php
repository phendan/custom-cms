<?php

namespace App;

class Request {
    private array $pageParams;
    private array $getParams;
    private array $postParams;

    public function __construct(array $pageParams)
    {
        $this->pageParams = $pageParams;
        $this->postParams = $_POST;
        $this->getParams = $_GET;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getInput(string $kind = 'post'): array
    {
        $input = match($kind) {
            'post' => $this->postParams,
            'get' => $this->getParams,
            'page' => $this->pageParams
        };

        return $this->sanitizeInput($input);
    }

    private function sanitizeInput(array $input): array
    {
        return array_map(function ($element) {
            return trim($element);
        }, $input);
    }
}
