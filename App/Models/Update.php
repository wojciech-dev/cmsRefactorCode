<?php declare(strict_types = 1);

namespace App\Models;

use PDO;

/**
 * Update model
 */
class Update extends \Core\Model {

    private string $tablename;
    private array $data;

    public function __construct($data, $tablename) {
        $this->data = $data;
        $this->tablename = $tablename;
    }

    public function edit(int $id): int {
        
        try {
            $sql = "UPDATE $this->tablename SET ";

            foreach ($this->data as $key => $value) {
                $sql .= "`" . $key . "` = :" . $key . ", ";
            }
    
            $pat = "+-0*/";
            $sql .= $pat;
            $sql = str_replace(", " . $pat, " ", $sql);
            $sql .= " WHERE `id` = $id";
    
            $query = static::getDB()->prepare($sql);
    
            foreach ($this->data as $key => $value) {
                $query->bindParam(":$key", $this->data[$key]);
            }

            $query->execute();

            return 1;

        } catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
    }

    public static function existsToken(string $token) {
        $stmt = static::getDB()->prepare("SELECT id, verified FROM users WHERE token=:token LIMIT 1");
        $stmt->execute(['token' => $token]); 
        return $stmt->fetch();
    }
}
