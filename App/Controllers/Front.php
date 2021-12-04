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

public function home($request, $response, $service, $app): void {
  
}


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

    if ($request->id) {
      $posts = $getBody->getRows(
        [
          'where'=>['id' => $request->id, 'status' => 1 ]
        ]
      );
    } else {
      $posts = $getBody->getRows(
        [
          'where'=>['parent_id' => $id['id'] ?? null, 'status' => 1 ]
        ]
      );
    }


    View::renderTemplate('front/index.html', [
      'menu' => $menu,
      'posts' => $posts,
      'url_more' => $request->title,
      'hidden_more' => $request->id ? 0 : 1
    ]);

  }
}
