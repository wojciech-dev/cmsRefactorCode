<?php

namespace App\Helpers;

class Upload {

  public static function uploadPhoto() {
    $target_dir = $_SERVER["DOCUMENT_ROOT"].'/uploads/';
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    if (isset($_POST["submit"])) {
      $check = getimagesize($_FILES["file"]["tmp_name"]);
      if ($check !== false) {
          echo "<div class='alert ok'>File is an image - " . $check["mime"] . ".</div>";
          $uploadOk = 1;
      } else {
          echo "<div class='alert error'>File is not an image.</div>";
          $uploadOk = 0;
      }
    }
    
    if (file_exists($target_file)) {
      echo "<div class='alert error'>Sorry, file already exists.</div>";
      $uploadOk = 0;
    }
    
    if (empty($target_file)) {
      echo "<div class='alert error'>Empty.</div>";
      $uploadOk = 0;
    }
    
    if ($_FILES["file"]["size"] > 200000) {
      echo "<div class='alert error'>Sorry, your file is too large.</div>";
      $uploadOk = 0;
    }
    
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      echo "<div class='alert error'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
      $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
      echo "<div class='alert error'>Sorry, your file was not uploaded.</div>";
    } else {
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "<div class='alert ok'>The file ". basename( $_FILES["file"]["name"]). " has been uploaded.</div>";
      } else {
        echo "<div class='alert error'>Sorry, there was an error uploading your file.</div>";
      }
    }
  }
}              
?>