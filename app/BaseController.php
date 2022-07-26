<?php

namespace App;

use App\View;
use App\Models\Database;
use App\Models\User;
use App\Request;

abstract class BaseController {
    protected Database $db;
    protected User $user;
    protected View $view;

    public function __construct()
    {
        $this->db = new Database;
        $this->user = new User($this->db);
        $this->view = new View($this->user);

        if ($this->user->isLoggedIn()) {
            $this->user->find($this->user->getId());
        }
    }

    abstract public function index(Request $request);

    protected function redirectTo(string $path)
    {
        header('Location: ' . $path);
        exit();
    }
}
