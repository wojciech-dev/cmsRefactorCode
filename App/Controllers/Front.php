<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Functions};
use App\Models\{Select, MenuTree, Registration, LoginModel, Update};

class Front extends \Core\Controller {

  public function index($request, $response, $service, $app): void {

    $errors= [];

    if (isset($_GET['token'])) {
      $errors= $this->verifyEmail();
    }

    if (isset($_POST['submit']) && $request->title == 'register') {
      $errors= $this->register($request->paramsPost());
    }

    if (isset($_POST['submit']) && $request->title == 'login') {
      $errors= $this->login($request->paramsPost());
    }

    $getMenu = new Select('menu');
    $getBody = new Select('body');
    $getBanner = new Select('banner');
    $getBox = new Select('box');

    //404
    if (!preg_match("/verify_email/i", $request->title)) {
      $slug = $getMenu->getRows(['select' => 'slug, reverse', 'where'=>['slug'=> $request->title], 'type'=>'fetch']);

      if (empty($slug)) {
        $errors = ['404 pabe not found'];
      }
    }

    $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));
    $id = $getMenu->getRows(['select' => 'id', 'where'=>['slug'=> $request->title], 'type'=>'fetch']);

    if (!preg_match("/start/i", $request->title)) {
      $inheritance = $getBody->getRows(['where'=>['status' => 1, 'inheritance' => 1]]);
    }

    //Functions::debugFunc($slug);
    
    if ($request->id) {
      $posts = $getBody->getRows(['where'=>['id' => $request->id, 'status' => 1 ]]);
    } else {
      $posts = $getBody->getRows([
        'where'=>['parent_id' => $id['id'] ?? null, 'status' => 1],
        'order_by'=> 'listorder DESC'
      ]);
      $banners = $getBanner->getRows(['where'=>['parent_id' => $id['id'] ?? null, 'status' => 1]]);
      $box = $getBox->getRows(['where'=>['parent_id' => $id['id'] ?? null, 'status' => 1],'order_by'=> 'listorder ASC']);
    }

    if (!preg_match("/admin/i", $request->title)) {

      View::renderTemplate("front/" . ($request->id ? 'card' : 'index') . ".html", [
        'menu' => $menu,
        'posts' => $posts ?? null,
        'banners' => $banners ?? null,
        'box' => $box ?? null,
        'path' => $request->title,
        'hidden_more' => $request->id ? 0 : 1,
        'inheritance' => $inheritance ?? null,
        'breadcrumbs' => $slug ? Functions::lastUrl($_SERVER['REQUEST_URI']) : '',
        'errors' => $errors,
        'session' => $_SESSION['username'] ?? null,
        'reverse' => $slug['reverse'] ?? null
      ]);
      
    }

  }

  //register
  public function register($data)  {

    $errors= [];

    $register = new Registration($data);
    $reg = $register->save();

    if (!$reg) {
        $errors= $register->state;
    }
    
    return $errors;
  }

  //login
  public function login($data) {
    $errors= [];

    $login = new LoginModel($data);
    $log = $login->save();

    if (!$log) {
        $errors= $login->state;
    }

    return $errors;
    
  }

  public function verifyEmail() {
    $errors= [];

    $update = Update::existsToken($_GET['token']);

    if ($update) {

        $up = new Update(['verified'=> 1], 'users');
        $up->edit($update['id']);

        $errors= ["<div class='alert-success'>Success</div>"];
    } else {
        $errors= ["Email address has not been verified"];
    }

    return $errors;

  }

}
