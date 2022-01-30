<?php  declare(strict_types = 1);

namespace App\Models;

use PDO;
/**
 * Menu
 */
class MenuTree extends \Core\Model {

  public static function has_children($rows, $id) {
    foreach ($rows as $row) {
      if ($row['parent_id'] == $id)
        return true;
    }
    return false;
  }
  //admin/menu nested tree
  public static function build_menu_main($rows, $parent=0) {  
    $result = "";
    if ($rows) {
      foreach ($rows as $row) {
        $status = $row['status'] ? '<span style="color: green">active</span>' : '<span style="color: red">disactive</span>';
        if ($row['parent_id'] == $parent) {
          $result.= "<tr class='row{$row['parent_id']}'>
              <th scope='row'>{$row['id']}</th>
              <td>{$row['title']}</td>
              <td>{$row['slug']}</td>
              <td class='status'>{$status}</td>
              <td>
              <div class='buttons'>
                <a 
                  href='/admin/banner/{$row['id']}' 
                  class='btn-outline'>
                  Banner <span class='badge'>".self::counter($row['id'], 'banner')."</span></a> 
                <a 
                  href='/admin/body/{$row['id']}' 
                  class='btn-outline'>
                  Body <span class='badge'>".self::counter($row['id'], 'body')."</span></a>
                <a 
                  href='/admin/box/{$row['id']}' 
                  class='btn-outline'>
                  Box <span class='badge'>".self::counter($row['id'], 'box')."</span></a>
                <a 
                  href='/admin/menu/edit/{$row['id']}' 
                  class='btn-outline'
                >Edit</a>
                <a 
                  href='/admin/menu/delete/{$row['id']}' 
                  class='btn btn-danger'
                  onclick=\"return confirm('Are you sure want to delete?');\"
                >X</a>
              </div>
              </td></tr>

          ";
          if (self::has_children($rows,$row['id'])) {
            $result.= self::build_menu_main($rows,$row['id']);
          }
        }
      }
    }

    return $result;
  }

  public static function counter($id, $table) { 
    $count = new Select($table);
    $sum = $count->getRows(
      [
      'select' => 'parent_id',
      'where'=>['parent_id'=> $id],
      'type'=>'count',
      ]
    );
    return $sum ?  $sum : 0;
  }

 
  public static function build_menu_left($rows, $parent=0) {  
    $result = "<ul>";
    if ($rows) {
      foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
          $result.= "
          <li><a href=/admin/body/{$row['id']} class=".(self::activeMenu($_SERVER['REQUEST_URI']) == $row['id'] ? 'active' : '').">{$row['title']}</a>";
          if (self::has_children($rows,$row['id'])) {
            $result.= self::build_menu_left($rows,$row['id']);
          }
        }
      }
    }
    $result.= "</li></ul>";
    return $result;
  }

  public static function activeMenu($request_uri) {
    $link_array = explode('/', $request_uri);
    return end($link_array);
  }

  public static function isActiveMenu($row) {
    if (self::activeMenu($_SERVER['REQUEST_URI']) == self::activeMenu($row)) {
      return 'active';
    }
  }

  //front-end menu tree
  public static function buildMenuInFront($rows, $parent=0) {  
    $result = "";
    if ($rows) {
      foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
          $result.= "
            <li class='link'>
              <a 
                class='item ".(self::isActiveMenu($row['slug']))."' 
                href='/{$row['slug']}'
              >{$row['title']}</a>";
          if (self::has_children($rows,$row['id']))
            $result.= "<ul class='sub-menu'>".self::buildMenuInFront($rows,$row['id'])."</ul>";
          $result.= "</li>";
        }
      }
    }
    return $result;
  }
}


