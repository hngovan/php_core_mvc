<?php

namespace app\controllers;

use app\middleware\AuthMiddleware;
use app\models\Post;
use core\Application;
use core\Controller;
use core\Request;

class PostsController extends Controller
{
  public function __construct()
  {
    $this->registerMiddleware(new AuthMiddleware());
  }

  public function index()
  {
    $posts = (new Post())->findAll(['status' => 1]);

    // Convert to Post objects
    $postObjects = [];
    foreach ($posts as $post) {
      $postObj = new Post();
      foreach ($post as $key => $value) {
        $postObj->$key = $value;
      }
      $postObjects[] = $postObj;
    }

    return $this->render('posts/index', [
      'title' => 'All Posts',
      'posts' => $postObjects
    ]);
  }

  public function create(Request $request): string
  {
    $post = new Post();

    if ($request->isPost()) {
      $post->loadData($request->getBody());

      if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadedFileName = $post->uploadImage($_FILES['image']);
        if ($uploadedFileName === false) {
          $post->addError('image', 'Invalid image file. Please upload JPG, PNG, or GIF (max 5MB)');
        }
      }

      if ($post->validate() && $post->save()) {
        Application::$app->session->setFlash('success', 'Post created successfully!');
        Application::$app->response->redirect('/posts');
        return '';
      }
    }

    return $this->render('posts/create', [
      'title' => 'Create New Post',
      'model' => $post
    ]);
  }

  public function view(Request $request): string
  {
    $id = $request->getRouteParam('id');
    $post = (new Post())->findOne(['id' => $id]);

    if (!$post) {
      Application::$app->response->setStatusCode(404);
      return $this->render('errors/_error', [
        'title' => 'Post Not Found'
      ]);
    }

    // Convert to Post object
    $postObj = new Post();
    foreach ($post as $key => $value) {
      $postObj->$key = $value;
    }

    return $this->render('posts/view', [
      'title' => $postObj->title,
      'post' => $postObj
    ]);
  }

  public function edit(Request $request): string
  {
    $id = $request->getRouteParam('id');
    $post = (new Post())->findOne(['id' => $id]);

    if (!$post) {
      Application::$app->response->setStatusCode(404);
      return $this->render('errors/_error', [
        'title' => 'Post Not Found'
      ]);
    }

    // Convert to Post object
    $postObj = new Post();
    foreach ($post as $key => $value) {
      $postObj->$key = $value;
    }

    // Check permission

    if (!$postObj->canEdit()) {
      Application::$app->session->setFlash('error', 'You are not authorized to edit this post!');
      Application::$app->response->redirect('/posts');
      return '';
    }

    if ($request->isPost()) {
      $postObj->loadData($request->getBody());

      if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($postObj->image) {
          $oldImagePath = Application::$ROOT_DIR . '/public/uploads/' . $postObj->image;
          if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
          }
        }

        $uploadedFileName = $postObj->uploadImage($_FILES['image']);
        if ($uploadedFileName === false) {
          $postObj->addError('image', 'Invalid image file. Please upload JPG, PNG, or GIF (max 5MB)');
        }
      }

      if ($postObj->validate() && $postObj->save()) {
        Application::$app->session->setFlash('success', 'Post updated successfully!');
        Application::$app->response->redirect('/posts/' . $postObj->id);
        return '';
      }
    }

    return $this->render('posts/edit', [
      'title' => 'Edit Post: ' . $postObj->title,
      'model' => $postObj
    ]);
  }

  public function delete(Request $request)
  {
    if (!$request->isPost()) {
      Application::$app->response->setStatusCode(405);
      return '';
    }

    $id = $request->getRouteParam('id');
    $post = (new Post())->findOne(['id' => $id]);

    if (!$post) {
      Application::$app->session->setFlash('error', 'Post not found!');
      Application::$app->response->redirect('/posts');
      return '';
    }

    // Convert to Post object
    $postObj = new Post();
    foreach ($post as $key => $value) {
      $postObj->$key = $value;
    }

    // Check permission
    if (!$postObj->canEdit()) {
      Application::$app->session->setFlash('error', 'You are not authorized to delete this post!');
      Application::$app->response->redirect('/posts');
      return '';
    }

    if ($postObj->deletePost()) {
      Application::$app->session->setFlash('success', 'Post deleted successfully!');
    } else {
      Application::$app->session->setFlash('error', 'Failed to delete post!');
    }

    Application::$app->response->redirect('/posts');
    return '';
  }
}
