<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Validation, Functions, Uploader, Upload, Posts};
use App\Models\{Select, Add, Delete, Update, MenuTree};
use PDO;

class Body extends \Core\Controller {
  /*
   * Body content
   * @return void
   */
  public function index($request, $response, $service, $app): void {
    $section = $request->section;
    $section_post = $section.'_post';
    $getMenu = new Select('menu');
    $getBody = new Select($section);
    $menu_left = MenuTree::build_menu_left($getMenu->getRows());
    $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $request->id],'type'=>'fetch']);

    switch ($request->action) {
      case "create":
        if (isset($_POST['submit'])) {
          //Functions::debugFunc($request->paramsPost());
          $add = new Add($section);
          $add->save(Posts::$section_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ));
          Functions::redirect("/admin/$section/{$request->id}");
        }
        View::renderTemplate('admin/content/'.$section.'Form.html',[
          'menu_left' => $menu_left,
          'header' => 'Add '.$section,
          'breadcrumbs' => $breadcrumbs['title'],
        ]);
        break;
      case "edit":
        $update = $getBody->getRows(['where'=>['id'=> $request->id], 'type'=>'fetch']);
        $breadcrumbs = $getMenu->getRows(['select' => 'title','where'=>['id'=> $update['parent_id']],'type'=>'fetch']);
        if (isset($_POST['submit'])) {
          $edit = new Update(Posts::$section_post(
            $request,
            $request->paramsPost(),
            $request->files()
          ), $section);
          $edit->edit($update['id']);
          Functions::redirect("/admin/$section/{$update['parent_id']}");
        }
        View::renderTemplate('admin/content/'.$section.'Form.html',[
            'menu_left' => $menu_left,
            'header' => "Edit item",
            'breadcrumbs' => $breadcrumbs['title'],
            'item' => $update,
        ]);
        break;
      case "delete":
            $delBody = new Delete($section);
            $delBody->del($request->id);
            $service->back();
          break;
        default:
        $items = $getBody->getRows([
          'where'=>['parent_id'=> $request->id],
          'order_by'=> 'listorder ASC',
        ]);
        View::renderTemplate('admin/content/'.$section.'.html', [
            'items' => $items,
            'menu_left' => $menu_left,
            'breadcrumbs' => $breadcrumbs['title'],
            'request_id' => $request->id,
        ]);
    }
  }

  public function sortList() {
    $array = $_POST['arrayorder'];
    if ($_POST['update']) {
        $count = 1;
        foreach ($array as $idval) {
            $sql = Update::sortable($_POST['section'], $count, $idval);
            $count ++; 
        }
    }
  }
}
?>