<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Helpers\{Functions};
use App\Models\{Select};

class DeletePhoto extends \Core\Controller {


public function remove($request) {

    $param = explode('&', $request->field);

    $data = [
        'field' => $param[0],
        'name' => str_replace("_", ".", $param[1]),
        'id' => $param[2],
        'table' => $param[3]
    ];
    
    try {
        $this->onFilesRemoveCallback($data['name']);
        $up = new Select($data['table']);
        $up->delPhoto($data['field'], $data['id']);
        Functions::redirect($_SERVER["HTTP_REFERER"]);
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    }
    
    public function onFilesRemoveCallback($removed_files) {
        $file = $_SERVER['DOCUMENT_ROOT'].'/uploads/' . $removed_files;
        if (file_exists($file))  {
            unlink($file);
        }
        return $removed_files;
    }
}


?>


