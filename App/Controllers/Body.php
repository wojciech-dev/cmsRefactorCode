<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Alerts, Functions, Uploader, Upload, Posts};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class Body extends \Core\Controller {
  /*
   * Body content
   * @return void
   */
  public function index($request, $response, $service, $app): void {

    $getMenu = new Select('menu');
    $getBody = new Select('body');
    $menu_left = MenuTree::build_menu_left($getMenu->getRows());
    $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $request->id],'type'=>'fetch']);

    switch ($request->action) {
      case "create":
        if (isset($_POST['submit'])) {
          $add = new Add('body');
          $add->save(Posts::body_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ));
          Functions::redirect("/admin/body/{$request->id}");
        }
        View::renderTemplate('admin/bodyForm.html',[
          'menu_left' => $menu_left,
          'header' => 'Add body',
          'breadcrumbs' => $breadcrumbs['title'],
        ]);
        break;
      case "edit":
        $update = $getBody->getRows(['where'=>['id'=> $request->id], 'type'=>'fetch']);
        $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $update['parent_id']],'type'=>'fetch']);
        if (isset($_POST['submit'])) {
          $edit = new Update(Posts::body_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ), 'body');
          $edit->edit($update['id']);
          Functions::redirect("/admin/body/{$update['parent_id']}");
        }
        View::renderTemplate('admin/bodyForm.html',[
            'menu_left' => $menu_left,
            'header' => "Edit item:",
            'breadcrumbs' => $breadcrumbs['title'],
            'item' => $update,
        ]);
        break;
      case "delete":
            $delBody = new Delete('body');
            $delBody->del($request->id);
            $service->back();
          break;
        default:
        $items = $getBody->getRows(['where'=>['parent_id'=> $request->id]]);
        View::renderTemplate('admin/body.html', [
            'items' => $items,
            'menu_left' => $menu_left,
            'breadcrumbs' => $breadcrumbs['title'],
            'request_id' => $request->id,
        ]);
    }
  }
}
?>