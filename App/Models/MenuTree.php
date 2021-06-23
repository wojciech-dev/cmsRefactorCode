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
  public static function build_menu($rows, $parent=0) {  
    $result = "<tbody>";
    if ($rows) {
      foreach ($rows as $row) {
        $status = $row['status'] ? '<span style="color: green">active</span>' : '<span style="color: red">disactive</span>';
        if ($row['parent_id'] == $parent) {
          $result.= "
            <tr>
              <td>{$row['title']}</td>
              <td>{$row['slug']}</td>
              <td class='status'>{$status}</td>
              <td>
              <div class='buttons'>
                <a href='/admin/services/{$row['id']}' class='btn-link'>Services <strong>( ".self::counter($row['id'], 'services')." )</strong></a>
                <a href='/admin/body/{$row['id']}' class='btn-link'>Body <strong>( ".self::counter($row['id'], 'body')." )</strong></a>
                <a href='/admin/banner/{$row['id']}' class='btn-link'>Banners <strong>( ".self::counter($row['id'], 'banner')." )</strong></a>
                <a href='/admin/menu/edit/{$row['id']}' class='btn-link'>Edit</a>
                <a 
                  href='/admin/menu/delete/{$row['id']}' 
                  class='btn-link'
                  onclick=\"return confirm('Are you sure want to delete?');\"
                >
                Delete</a>
              </div>
              </td>
            </tr>
          ";
          if (self::has_children($rows,$row['id'])) {
            $result.= "
            <tr class='nested'>
              <td colspan='4'>
                <table class='table table-nested'>".self::build_menu($rows,$row['id'])."</table>
              </td>
            </tr>
            ";
          }
        }
      }
    }
    $result.= "</tbody>";
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

  //admin/menu nested tree
  public static function build_menu_left($rows, $parent=0) {  
    $result = "<ul>";
    if ($rows) {
      foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
          $result.= "
          <li>{$row['title']}";
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


  //front menu tree
  public static function buildMenuInFront($rows, $parent=0) {  
    $result = "";
    if ($rows) {
      foreach ($rows as $row) {
        if ($row['parent_id'] == $parent) {
          $result.= "
            <li class='nav__item'>
              <a 
                class='nav__link".(self::activeMenu() == $row['slug'] ? ' active' : '')."' 
                href='/pl/{$row['slug']}'
              >{$row['title']}</a>";
          if (self::has_children($rows,$row['id']))
            $result.= "<ul class='nav-dropdown'>".self::buildMenuInFront($rows,$row['id'])."</ul>";
          $result.= "</li>";
        }
      }
    }
    return $result;
  }
}


