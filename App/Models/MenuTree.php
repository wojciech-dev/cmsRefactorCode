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
          <li><a href=/admin/body/{$row['id']} class=".(self::lastElUri() == $row['id'] ? 'active' : '').">{$row['title']}</a>";
          if (self::has_children($rows,$row['id'])) {
            $result.= self::build_menu_left($rows,$row['id']);
          }
        }
      }
    }
    $result.= "</li></ul>";
    return $result;
  }

  public static function activeMenu() {
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    $first_part = $components[2] ?? null;
    return $first_part;
  }

  public static function lastElUri() {
    $link = $_SERVER["REQUEST_URI"];
    $link_array = explode('/', $link);
    return end($link_array);
  }

  //front menu tree
  public static function buildMenuInFront($rows, $parent=0) {  
    $result = "";
    if ($rows) {
      foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
          $result.= "
            <li>
              <a 
                class='".(self::activeMenu() == $row['slug'] ? ' active' : '')."' 
                href='/{$row['slug']}'
              >{$row['title']}</a>";
          if (self::has_children($rows,$row['id']))
            $result.= "<ul>".self::buildMenuInFront($rows,$row['id'])."</ul>";
          $result.= "</li>";
        }
      }
    }
    return $result;
  }
}


