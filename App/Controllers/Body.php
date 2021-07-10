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

  public function delPhoto($request) {
    $param = explode('&', $request->field);
    $data = [
        'field' => $param[0],
        'name' => $param[1],
        'id' => $param[2],
        'table' => $param[3]
    ];
    try {
        $this->onFilesRemoveCallback($data['name']);
        $up = new Select($data['table']);
        $up->delPhoto($data['field'], $data['id']);
        Functions::redirect($_SERVER["HTTP_REFERER"]);
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  public function onFilesRemoveCallback($removed_files) {
    $file = $_SERVER['DOCUMENT_ROOT'].'/uploads/' . $removed_files;
    if (file_exists($file))  {
        unlink($file);
    }
    return $removed_files;
  }
}
?>
