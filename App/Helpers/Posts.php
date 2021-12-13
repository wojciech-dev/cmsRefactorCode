<?php

namespace App\Helpers;
use App\Helpers\{Validation, Functions, Alerts, Uploader};
use App\Models\{Select};

class Posts {

  public static function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

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

    $file1 = $uploader->upload($files['file1'], ['uploadDir' => $path]);
    $file2 = $uploader->upload($files['file2'], ['uploadDir' => $path]);
    $file3 = $uploader->upload($files['file3'], ['uploadDir' => $path]);
    $file4 = $uploader->upload($files['file4'], ['uploadDir' => $path]);

    if ($file1 || $file2 || $file3 || $file4) {
      $data['photo1'] = $file1['data']['metas'][0]['name'] ?? null;
      $data['photo2'] = $file2['data']['metas'][0]['name'] ?? null;
      $data['photo3'] = $file3['data']['metas'][0]['name'] ?? null;
      $data['photo4'] = $file4['data']['metas'][0]['name'] ?? null;
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

      'description' => $data['description'],

      'slug' => $slug ? Functions::slugify($slug) : null,

      'status' => isset($data['status']) ? 1 : 0,

      'more' => isset($data['more']) ? 1 : 0,

      'layout'    => intval($data['layout']),

      'photo1' => $data['file1'] ?? $photo['photo1'],

      'photo2' => $data['file2'] ?? $photo['photo2'],

      'photo3' => $data['file3'] ?? $photo['photo3'],

      'photo4' => $data['file4'] ?? $photo['photo4'],
    ]; 

    if (!$val->isSuccess()) {
      Alerts::dangerAlert("Error", $val->displayErrors());
    }
    return $data;
  }

  public static function banner_post($request, $data, $files) {

    $val = new Validation();
    $photo = Posts::photo($files);
    $tab = [];

    $val->name('name')->value($data['name'])->pattern('text')->required();

    $val->name('title')->value($data['title'])->pattern('text');

    $val->name('more_link')->value($data['more_link'])->pattern('url');

    if ($val->isSuccess()) {
    	echo "Validation ok!";

      $tab = [
        'parent_id' => $request->action == 'edit' ? $data['parent'] : $request->id,
        'name' =>      $data['name'],
        'title' =>     $data['title'],
        'description' => $data['description'],
        'more_link' => $data['more_link'],
        'status' => isset($data['status']) ? 1 : 0,
        'layout'    => intval($data['layout']),
        'photo1' => $data['file1'] ?? $photo['photo1']
      ];
      
    } else {
      echo $val->displayErrors();
    }

    return $tab;
  }

}              
?>