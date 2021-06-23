<?php

namespace App\Helpers;

class Alerts {
  /**
   * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
   * @param  String $message - What will be used for the main description in the alert
   */
  public static function successAlert($bold, $message) {
    echo "
    <div class='alert'>
      <div class='alert alert-success'>
        <span class='closebtn' 
        onclick=\"return this.parentElement.style.display='none';\"
          >&times;</span> 
        <strong>{$bold}</strong> {$message}
      </div>
    </div>
    ";
  }

  /**
   * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
   * @param  String $message - What will be used for the main description in the alert
   */
  public static function dangerAlert($bold, $message) {
    echo "
    <div class='alert alert-danger'>
       <span 
        class='closebtn' 
        onclick=\"return this.parentElement.style.display='none';\"
        >&times;</span> 
        <strong>{$bold}</strong> {$message}
    </div>
    ";
  }
}              
?>


