<?php

namespace App\Helpers;
use \DateTime;

class Functions {
    public static function slugify($text) {
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      $text = preg_replace('~[^-\w]+~', '', $text);
      $text = trim($text, '-');
      $text = preg_replace('~-+~', '-', $text);
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return $text;
    }

    public static function getDateTime() {
      $date = new DateTime();
      return $date->format('Y-m-d H:i:s');
    }

    public static function current_url() {
      $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      return str_replace("&", "&amp", $url);
    }

    public static function lastUrl($url) {
      return array_slice(explode('/', $url), -1)[0];
    }

    public static function redirect($url, $permanent = false) {
      header('Location: ' . $url, true, $permanent ? 301 : 302);
      exit();
    }

    public static function debugFunc($data) {
      echo "<pre>";
      print_r($data);
      echo "</pre>";
      //exit;
    }
}
