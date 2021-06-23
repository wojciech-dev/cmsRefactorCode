<?php  declare(strict_types = 1);

namespace App\Models;

use PDO;
use App\Helpers\{Validation, Alerts};

class LoginModel extends \Core\Model {

    private $username;
    private $password;

    public function __construct($data) {
        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    public function loginUser() {
        
        $val = new Validation();

        $user = $val->name('username')->value($this->username)->pattern('alpha')->required() ? $this->username : null;
        $pass = $val->name('password')->value($this->password)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->required() ? $this->password : null;


        if ($val->isSuccess()) {
            $sql = "SELECT * FROM users 
            WHERE 
            username = :username";

            try {

                $query = static::getDB()->prepare($sql);
                $query->execute([
                    ':username' => $user
                ]);
                $result = $query->fetch();

                if ($query->rowCount() > 0) {
                    if (password_verify($pass , $result['password'])) {
                        $_SESSION['username'] = $result['username'];
                        $_SESSION['type'] = $result['type'];
                        if ($result['type'] == 'user') {
                            header("Location:user");
                        } else {
                            header("Location:menu");
                        }
                        exit();
                    } else {
                        Alerts::dangerAlert("Error","Login or password incorrect");
                    }
                } else {
                    Alerts::dangerAlert("Error","No user");
                }
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            Alerts::dangerAlert("Error","Incorrect data");
        }
    }
}
