<?php

namespace App\Controllers;

use App\View;
use App\BaseController;
use App\Request;
use App\Models\FormValidation;
use App\Models\Database;
use App\Models\User;

class RegisterController extends BaseController {
    public function index(Request $request)
    {
        $db = new Database;

        if ($request->getMethod() === 'POST') {
            $formInput = $request->getInput();
            $validation = new FormValidation($formInput, $db);

            $validation->setRules([
                'firstName' => 'required|min:2|max:32',
                'lastName' => 'required|min:2|max:32',
                'email' => 'required|email|available:users',
                'password' => 'required|min:6',
                'passwordAgain' => 'required|matches:password'
            ]);

            $validation->validate();

            if ($validation->fails()) {
                $this->view->render('register', [
                    'errors' => $validation->getErrors()
                ]);
                return;
            }

            $user = new User($db);
            $user->register($formInput);
        }

        $this->view->render('register');
    }
}
