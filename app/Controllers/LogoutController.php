<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;

class LogoutController extends BaseController {
    public function index(Request $request)
    {
        if ($this->user->isLoggedIn()) {
            $this->user->logout();
        }

        $this->redirectTo('/');
    }
}
