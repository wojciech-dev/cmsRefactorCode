<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Alerts, Functions};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class Front extends \Core\Controller {

  /**
   * @return void
   */

  public function index($request, $response, $service, $app): void {

    $getMenu = new Select('menu');

    $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));


    View::renderTemplate('front/index.html', [
      'menu' => $menu,
    ]);
  

  }
}
