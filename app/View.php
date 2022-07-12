<?php

namespace App;

class View {
    public function render(string $view, array $data = [])
    {
        extract($data);

        require_once '../app/Views/partials/header.php';
        require_once "../app/Views/{$view}.php";
        require_once '../app/Views/partials/footer.php';

        exit();
    }
}
