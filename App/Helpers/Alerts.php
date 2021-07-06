<?php

namespace App\Helpers;

class Alerts {
  /**
   * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
   * @param  String $message - What will be used for the main description in the alert
   */
  public static function successAlert($bold, $message) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>$bold</strong> $message
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>
    ";
  }

  /**
   * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
   * @param  String $message - What will be used for the main description in the alert
   */
  public static function dangerAlert($bold, $message) {
    echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
      <strong>$bold</strong> $message
      <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span>
      </button>
    </div>
    ";
  }
}              
?>


