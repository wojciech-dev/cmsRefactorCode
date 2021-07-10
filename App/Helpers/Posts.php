<?php

namespace App\Helpers;
use App\Helpers\{Validation, Functions, Alerts, Uploader};
use App\Models\{Select};

class Posts {

  public static function menu_post($data) {

    $val = new Validation();
    $getMenu = new Select('menu');

    $link = $getMenu->getRows(
        [
        'select' => 'slug',
        'where'=>['id'=>$data['category']],
        'type' => 'fetch'
        ]
    );

    $slug = $val
              ->name('title')
              ->value($data['title'])
              ->pattern('text')
              ->required() ? $data['title'] : null;

    $data = [
      'parent_id' => $val
                      ->name('category')
                      ->value($data['category'])
                      ->pattern('int') ? $data['category'] : null,

      'title'     => $val
                      ->name('title')
                      ->value($data['title'])
                      ->pattern('words')
                      ->required() ? $data['title'] : null,

      'slug'      => $link ? $link['slug'].'/'.Functions::slugify($slug) : Functions::slugify($slug),

      'status'    => $val
                      ->name('status')
                      ->value($data['status'])
                      ->pattern('int')
                      ->required() ? $data['status'] : null,
    ];
    
    if (!$val->isSuccess()) {
        Alerts::dangerAlert("Error", $val->displayErrors());
    }
    return $data;
  }

  public static function photo($files) {
    $path = $_SERVER["DOCUMENT_ROOT"].'/uploads/';
    $uploader = new Uploader();
    $errors = '';

    $file = $uploader->upload($files['file'], ['uploadDir' => $path]);

    if ($file) {
      $data['photo1'] = $file['data']['metas'][0]['name'] ?? null;
      if ($file['hasErrors']) {
        $errors = $file['errors'];
        foreach ($errors as $item) {
          Alerts::dangerAlert("Error!","{$item[0]}");
        }
      }
      return $data;
    }
  }

  public static function body_post($request, $data, $files) {

    $val = new Validation();
    $photo = Posts::photo($files);

    $slug = $val
              ->name('name')
              ->value($data['name'])
              ->pattern('text')
              ->required() ? $data['name'] : null;
              
    $data = [
      'parent_id' => $request->action == 'edit' ? $data['parent'] : $request->id,

      'name' => $val
                  ->name('name')
                  ->value($data['name'])
                  ->pattern('words') ? $data['name'] : null,

      'title' => $val
                  ->name('title')
                  ->value($data['title'])
                  ->pattern('words') ? $data['title'] : null,

      'content' => $data['content'],

      'slug' => $slug ? Functions::slugify($slug) : null,

      'status' => isset($data['status']) ?? 1,

      'hidden' => isset($data['more']) ?? 1,

      'photo1' => $data['photo1'] ?? $photo['photo1'],
    ]; 

    if (!$val->isSuccess()) {
      Alerts::dangerAlert("Error", $val->displayErrors());
    }
    return $data;
  }
}              
?>