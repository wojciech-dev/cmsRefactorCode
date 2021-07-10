<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

/**
 * Delete model
 */
class Delete extends \Core\Model {
	
	private string $tablename;
	
    public function __construct($tablename) {
        $this->tablename = $tablename;
    }
	
	public function del(int $id) {
        try {
            $delete = static::getDB()->prepare("DELETE FROM $this->tablename WHERE `id` = $id");
            $delete->execute();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
	}

    public function delByParentId(int $id) {
        try {
            $delete = static::getDB()->prepare("DELETE FROM $this->tablename WHERE parent_id = $id");
            $delete->execute();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
	}
}
?>