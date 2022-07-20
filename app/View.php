<?php

namespace App;

use App\Models\User;
use App\Helpers\CSRFProtection;

class View {
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function render(string $view, array $data = [])
    {
        extract($data);
        $user = $this->user;

        $csrf = CSRFProtection::class;

        require_once '../app/Views/partials/header.php';
        require_once "../app/Views/{$view}.php";
        require_once '../app/Views/partials/footer.php';

        exit();
    }
}
