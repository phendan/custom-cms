<?php

namespace App\Controllers;

use App\BaseController;
use App\Request;
use App\Models\Database;
use App\Models\FormValidation;
use App\Models\User;
use Exception;

class LoginController extends BaseController {
    /**
     * GET Request on /login
     */
    public function index(Request $request)
    {
        if ($this->user->isLoggedIn()) {
            $this->redirectTo('/dashboard');
        }

        $this->view->render('login', [
            'scripts' => [ 'pages/login' ]
        ]);
    }

    /**
     * POST Request on /login to create a user session (login)
     */
    public function create(Request $request)
    {
        $formInput = $request->getInput();

        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->render('login', [
                'errors' => $validation->getErrors()
            ]);
            return;
        }

        try {
            $this->user->login($formInput);
            $this->redirectTo('/dashboard');
        } catch (Exception $e) {
            //$e->getMessage();
            $this->view->render('login', [
                'errors' => [
                    'root' => 'Email or password was not correct.'
                ]
            ]);
        }
    }
}
