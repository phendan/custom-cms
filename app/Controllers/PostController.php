<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Request;
use App\Models\FormValidation;
use App\Models\Post;

class PostController extends BaseController {
    public function index(Request $request)
    {
        //
    }

    public function create(Request $request)
    {
        if (!$this->user->isLoggedIn()) {
            $this->redirectTo('/');
        }

        if ($request->getMethod() !== 'POST') {
            $this->view->render('posts/create');
            return;
        }

        $formInput = $request->getInput();

        $validation = new FormValidation($formInput, $this->db);

        $validation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:100'
        ]);

        $validation->validate();

        if ($validation->fails()) {
            $this->view->render('posts/create', [
                'errors' => $validation->getErrors()
            ]);
        }

        $post = new Post($this->db);
        $post->create($this->user->getId(), $formInput);
        Session::flash('message', 'Your post has been successfully created');
        $this->redirectTo('/dashboard');
    }
}
