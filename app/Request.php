<?php

namespace App;

class Request {
    private array $pageParams;
    private array $getParams;
    private array $postParams;
    private array $fileParams;

    public function __construct(array $pageParams)
    {
        $this->pageParams = $pageParams;
        $this->postParams = $_POST;
        $this->getParams = $_GET;
        $this->fileParams = $_FILES;
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getInput(string $kind = 'post'): array
    {
        $input = match($kind) {
            'post' => $this->sanitizeInput($this->postParams),
            'get' => $this->sanitizeInput($this->getParams),
            'page' => $this->pageParams,
            'file' => $this->fileParams
        };

        return $input;
    }

    private function sanitizeInput(array $input): array
    {
        return array_map(function ($element) {
            return trim($element);
        }, $input);
    }
}
