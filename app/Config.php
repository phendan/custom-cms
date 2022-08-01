<?php

namespace App;

class Config {
    private static array $options = [
        'app' => [
            'uploadFolder' => 'images'
        ],
        'database' => [
            'host' => 'localhost',
            'name' => 'forum',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4'
        ]
    ];

    public static function get(string $selector)
    {
        $elements = explode('.', $selector);
        $dataset = self::$options;

        foreach ($elements as $element) {
            $dataset = $dataset[$element];
        }

        return $dataset;
    }
}
