<?php

namespace App\Helpers;

use App\Helpers\{Functions, Uploader};
use App\Models\{Select};

class Posts
{

  public static function menu_post($data)
  {
    $getMenu = new Select('menu');
    $link = $getMenu->getRows(
      [
        'select' => 'slug',
        'where' => ['id' => $data['category']],
        'type' => 'fetch'
      ]
    );

    return $data = [
      'parent_id' => $data['category'],
      'title'     => $data['title'],
      'slug'      => $link ? $link['slug'] . '/' . Functions::slugify($data['title']) : Functions::slugify($data['title']),
      'status'    => $data['status'],
      'reverse'   => isset($data['reverse']) ? 1 : 0,
    ];
  }

  public static function photo($files)
  {
    $path = $_SERVER["DOCUMENT_ROOT"] . '/uploads/';
    $uploader = new Uploader();

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

  public static function body_post($request, $data, $files)
  {
    $getBody = new Select('body');
    $photo = Posts::photo($files);
    return [
      'parent_id' => $request->action == 'edit' ? $data['parent'] : $request->id,
      'listorder' => $getBody->getRows(['type' => 'count']) + 1,
      'name' => $data['name'],
      'title' => $data['title'],
      'description' => $data['description'],
      'slug' => Functions::slugify($data['name']),
      'status' => isset($data['status']) ? 1 : 0,
      'more' => isset($data['more']) ? 1 : 0,
      'more_link' =>   $data['more_link'],
      'more_label' =>  $data['more_label'] ?: 'Read more',
      'layout'    => intval($data['layout']),
      'inheritance' => isset($data['inheritance']) ? 1 : 0,
      'add_form' => isset($data['add_form']) ? 1 : 0,
      'photo1' => $data['file1'] ?? $photo['photo1'],
      'photo2' => $data['file2'] ?? $photo['photo2'],
      'photo3' => $data['file3'] ?? $photo['photo3'],
      'photo4' => $data['file4'] ?? $photo['photo4']
    ];
  }

  public static function banner_post($request, $data, $files)
  {
    $getBanner = new Select('banner');
    $photo = Posts::photo($files);
    return [
      'parent_id' =>   $request->action == 'edit' ? $data['parent'] : $request->id,
      'listorder' =>   $getBanner->getRows(['type' => 'count']) + 1,
      'name' =>        $data['name'],
      'title' =>       $data['title'],
      'more_link' =>   $data['more_link'],
      'status' =>      isset($data['status']) ? 1 : 0,
      'layout' =>      intval($data['layout']),
      'photo1' =>      $data['file1'] ?? $photo['photo1']
    ];
  }

  public static function box_post($request, $data, $files)
  {
    $getBox = new Select('box');
    $photo = Posts::photo($files);
    return [
      'parent_id' =>   $request->action == 'edit' ? $data['parent'] : $request->id,
      'listorder' =>   $getBox->getRows(['type' => 'count']) + 1,
      'name' =>        $data['name'],
      'description' => $data['description'],
      'status' =>      isset($data['status']) ? 1 : 0,
      'photo1' =>      $data['file1'] ?? $photo['photo1'],
      'more_link' =>   $data['more_link'],
      'label_link' =>  $data['label_link'] ?: 'Read more',
      'color_bg' =>    $data['color']
    ];
  }

  public static function users_post($data)
  {
    return $data = [
      'username' => $data['username'],
      'email'     => $data['email'],
      'verified'    => isset($data['verified']) ? 1 : 0,
    ];
  }
}
