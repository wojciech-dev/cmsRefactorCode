<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Alerts, Functions, Uploader, Upload, Posts};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class Banner extends \Core\Controller {
  /*
   * Banner content
   * @return void
   */
  public function index($request, $response, $service, $app): void {

    $getMenu = new Select('menu');
    $getBody = new Select('banner');
    $menu_left = MenuTree::build_menu_left($getMenu->getRows());
    $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $request->id],'type'=>'fetch']);

    switch ($request->action) {
      case "create":
        if (isset($_POST['submit'])) {
          $add = new Add('banner');
          $add->save(Posts::banner_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ));
          Functions::redirect("/admin/banner/{$request->id}");
        }
        View::renderTemplate('admin/bannerForm.html',[
          'menu_left' => $menu_left,
          'header' => 'Add banner',
          'breadcrumbs' => $breadcrumbs['title'],
        ]);
        break;
      case "edit":
        $update = $getBody->getRows(['where'=>['id'=> $request->id], 'type'=>'fetch']);
        $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $update['parent_id']],'type'=>'fetch']);
        if (isset($_POST['submit'])) {
          $edit = new Update(Posts::banner_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ), 'banner');
          $edit->edit($update['id']);
          Functions::redirect("/admin/banner/{$update['parent_id']}");
        }
        View::renderTemplate('admin/bannerForm.html',[
            'menu_left' => $menu_left,
            'header' => "Edit banner:",
            'breadcrumbs' => $breadcrumbs['title'],
            'item' => $update,
        ]);
        break;
      case "delete":
            $delBody = new Delete('banner');
            $delBody->del($request->id);
            $service->back();
          break;
        default:
        $items = $getBody->getRows(['where'=>['parent_id'=> $request->id]]);
        View::renderTemplate('admin/banner.html', [
            'items' => $items,
            'menu_left' => $menu_left,
            'breadcrumbs' => $breadcrumbs['title'],
            'request_id' => $request->id,
        ]);
    }
  }
}
?>