<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;

class HomeController extends BaseController {
    public function index(Request $index)
    {
        $this->view->render('home');
    }
}
