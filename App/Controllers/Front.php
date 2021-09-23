<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Alerts, Functions};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class Front extends \Core\Controller {

  /**
   * Front controller
   * @return void
   */

  public function index($request, $response, $service, $app): void {

    $getMenu = new Select('menu');
    $getBody = new Select('body');

    $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));

    $id = $getMenu->getRows(
      [
        'select' => 'id',
        'where'=>['slug'=> $request->title],
        'type'=>'fetch',
      ]
    );

    $posts = $getBody->getRows(['where'=>['parent_id' => $id['id'] ?? null, 'status' => 1 ]]);


    View::renderTemplate('front/index.html', [
      'menu' => $menu,
      'posts' => $posts
    ]);
  

  }
}
