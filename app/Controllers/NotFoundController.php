<?php

namespace App\Controllers;

use App\Request;

class NotFoundController {
    public function index(Request $index)
    {
        echo 'Not found';
    }
}
