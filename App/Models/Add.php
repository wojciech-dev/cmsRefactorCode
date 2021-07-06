<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

/**
 * Add model
 */
class Add extends \Core\Model {

    private string $tablename;
	
    public function __construct($tablename) {
        $this->tablename = $tablename;
    }
	
    function save(array $data): void {
    	try {
            foreach ($data as $key => $value) {
                $keys[] = $key;
                $values[] = $value;
            }
        
            $tblKeys = implode(",", $keys);        
            $keyss = ':' . implode(",:", $keys);
            
            $query = static::getDB()->prepare("INSERT INTO $this->tablename ($tblKeys) VALUES ($keyss)");
            
            foreach ($keys as $key) {
                $query->bindParam(":$key", $data[$key]);
            }

            $query->execute();

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
?>

