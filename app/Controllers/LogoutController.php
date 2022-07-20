<?php

namespace App\Controllers;

use App\BaseController;

class LogoutController extends BaseController {
    public function index()
    {
        if ($this->user->isLoggedIn()) {
            $this->user->logout();
        }

        $this->redirectTo('/');
    }
}
