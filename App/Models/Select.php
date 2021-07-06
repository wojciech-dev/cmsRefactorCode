<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

class Select extends \Core\Model {
	
    private string $tablename;

    public function __construct($tablename) {
        $this->tablename = $tablename;
    }

    public function getRows($conditions = array()) {
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$this->tablename;
        if (array_key_exists("where",$conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }

        if (array_key_exists("order_by",$conditions)) {
            $sql .= ' ORDER BY '.$conditions['order_by'];
        }

        if (array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)) {
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit'];
        } elseif (!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)) {
            $sql .= ' LIMIT '.$conditions['limit'];
        }

        $query = static::getDB()->prepare($sql);
        
        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
   
        $query->execute();

        if (array_key_exists("type",$conditions) && $conditions['type'] != 'all') {
            switch ($conditions['type']) {
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'fetch':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        } else {
            if ($query->rowCount() > 0) {
                $data = $query->fetchAll();
            }
        }
        return !empty($data) ? $data : false;
    }

    public function UpdateMenu($id) {
        $id = intval($id);
        $query = static::getDB()->prepare("UPDATE $this->tablename SET `parent_id`= 0 WHERE `parent_id` = $id");
        $query->execute();
        return $query->fetch();
    }

    public function delPhoto($field, $id) {
        $query = static::getDB()->prepare("UPDATE $this->tablename SET $field = null WHERE `id` = :id");
        $query->execute(['id' => $id]);
        return $query->fetchAll();
    }

    public function getSelect($id) {
        $query = static::getDB()->prepare("SELECT * FROM $this->tablename WHERE `parent_id` <> $id AND id <> $id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}