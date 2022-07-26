<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;

class ContactController extends BaseController {
    public function index(Request $index)
    {
        echo 'Contact';
    }
}
