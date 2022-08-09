<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;

class AboutController extends BaseController {
    public function index(Request $request)
    {
        $this->view->render('about');
    }
}
