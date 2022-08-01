<?php

namespace App\Controllers;

use App\BaseController;
use App\Helpers\Session;
use App\Request;
use App\Models\FormValidation;
use App\Models\Post;
use App\Models\FileValidation;
use Exception;

class PostController extends BaseController {
    public function index(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('message', 'This post could not be found');
            $this->redirectTo('/');
        }

        $identifier = $request->getInput('page')[0];

        $post = new Post($this->db);

        if (!$post->find($identifier)) {
            Session::flash('message', 'This post could not be found');
            $this->redirectTo('/');
        }

        $this->view->render('posts/index', [
            'post' => $post
        ]);
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

        $formValidation = new FormValidation($formInput, $this->db);

        $formValidation->setRules([
            'title' => 'required|min:10|max:64',
            'body' => 'required|min:100'
        ]);

        $formValidation->validate();

        $fileInput = $request->getInput('file');

        $fileValidation = new FileValidation($fileInput);
        $fileValidation->setRules([
            'image' => 'required|type:image|maxsize:2097152'
        ]);
        $fileValidation->validate();

        if ($formValidation->fails() || $fileValidation->fails()) {
            $this->view->render('posts/create', [
                'errors' => array_merge(
                    $formValidation->getErrors(),
                    $fileValidation->getErrors()
                )
            ]);
        }

        $post = new Post($this->db);

        try {
            $post->create($this->user->getId(), $formInput, $fileInput['image']);
            Session::flash('message', 'Your post has been successfully created');
            $this->redirectTo('/dashboard');
        } catch (Exception $e) {
            $this->view->render('posts/create', [
                'errors' => [
                    'root' => $e->getMessage()
                ]
            ]);
        }
    }

    public function edit(Request $request)
    {
        if (!isset($request->getInput('page')[0])) {
            Session::flash('message', 'You must access this page via a link.');
            $this->redirectTo('/dashboard');
        }

        $identifier = $request->getInput('page')[0];
        $post = new Post($this->db);

        if (!$post->find($identifier)) {
            Session::flash('message', 'This post does not exist.');
            $this->redirectTo('/');
        }

        if (!$this->user->isLoggedIn() || $this->user->getId() !== $post->getUserId()) {
            Session::flash('message', 'You do not have permission to edit this post.');
            $this->redirectTo('/');
        }

        if ($request->getMethod() !== 'POST') {
            $this->view->render('/posts/edit', [
               'post' => $post
            ]);
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
            $this->view->render('posts/edit', [
                'errors' => $validation->getErrors()
            ]);
        }

        if (!$post->edit($formInput)) {
            Session::flash('message', 'Something went wrong');
            $this->view->render('posts/edit', [
                'post' => $post
            ]);
        }

        Session::flash('message', 'The post has been successfully updated');
        $this->redirectTo("/post/{$post->getId()}/{$post->getSlug()}");
    }

    public function delete(Request $request)
    {
        $getInput = $request->getInput('get');
        if (!isset($getInput['csrfToken']) || $getInput['csrfToken'] !== Session::get('csrfToken')) {
            Session::flash('message', 'This request did not seem intentional.');
            $this->redirectTo('/');
        }

        if (!isset($request->getInput('page')[0])) {
            Session::flash('message', 'You must access this page via a link.');
            $this->redirectTo('/dashboard');
        }

        $identifier = $request->getInput('page')[0];
        $post = new Post($this->db);

        if (!$post->find($identifier)) {
            Session::flash('message', 'This post has already been deleted.');
            $this->redirectTo('/');
        }

        if (!$this->user->isLoggedIn() || $this->user->getId() !== $post->getUserId()) {
            Session::flash('message', 'You do not have permission to delete this post.');
            $this->redirectTo('/');
        }

        if (!$post->delete()) {
            Session::flash('message', 'Something went wrong.');
            $this->redirectTo('/');
        }

        Session::flash('message', 'The post was successfully deleted.');
        $this->redirectTo('/dashboard');
    }
}
