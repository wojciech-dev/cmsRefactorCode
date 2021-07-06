<?php

namespace App\Helpers;
use App\Helpers\{Validation, Functions, Alerts};
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

}              
?>