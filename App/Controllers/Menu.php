<?php

namespace App\Controllers;

use \Core\{View};
use App\Helpers\{Validation, Functions, Posts};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class Menu extends \Core\Controller {
  /**
   * Menu controller
   * @return void
   */
  public function index($request, $response, $service, $app): void {

    $getMenu = new Select('menu');
    $menu_main = MenuTree::build_menu_main($getMenu->getRows());
    $menu_left = MenuTree::build_menu_left($getMenu->getRows());
    $show_title = $getMenu->getRows(['select' => 'id, title']);
    
    switch ($request->action) {
      case "create":
        if (isset($_POST['submit'])) {
          $add = new Add('menu');
          $add->save(Posts::menu_post($request->paramsPost()));
          Functions::redirect('/admin/menu');
        }
        View::renderTemplate('admin/menu/menuForm.html', [
          'menu_left' => $menu_left,
          'header' => 'Add item',
          'items' => $show_title,
        ]);
        break;
      case "edit":
        $update = $getMenu->getRows(['where'=>['id'=> $request->id],'type'=>'fetch']);
        $show_title = $getMenu->getSelect($update['id']);
        if (isset($_POST['submit'])) {
          $row = new Update(Posts::menu_post($request->paramsPost()), 'menu');
          $row->edit($update['id']);
          Functions::redirect('/admin/menu');
        }
        View::renderTemplate('admin/menu/menuForm.html', [
          'menu_left' => $menu_left,
          'header' => 'Edit item',
          'item' => $update,
          'items' => $show_title,
        ]);
        break;
      case "delete":
        $del = new Delete('menu');
        $del->del($request->id);
        $del->delByParentId($request->id);
        $delBody = new Delete('body');
        $delBody->delByParentId($request->id);
        Functions::redirect('/admin/menu');
        break;
      default:
      View::renderTemplate('admin/menu/menu.html', [
        'menu' => $menu_main,
        'menu_left' => $menu_left
      ]);
    }
  }

  public function logout($request, $response): void {
    unset($_SESSION['username']);  
    unset($_SESSION['type']);
    session_destroy();
    $response->redirect('/admin/');
  }
}