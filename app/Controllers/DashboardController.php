<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\Database;
use App\Models\User;

class DashboardController extends BaseController {
    public function index(Request $request)
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirectTo('/login');
        }

        $this->view->render('dashboard', [
            'user' => $this->user
        ]);
    }
}
