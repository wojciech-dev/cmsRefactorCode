<?php

namespace App\Models;

use PDO;
use App\Helpers\{Validation};
use App\Helpers\{Mailer};
/**
 * Register model
 */
class Registration extends \Core\Model {

    private string $email;
    private string $username;
    private string $password;
    public array $state;
    
    public function __construct($data) {
        $this->username = $data['username'];
        $this->email    = $data['email'];
        $this->password = $data['password'];
    }

    public function save(): void {
		
        $val = new Validation();

        $user = $val->name('username')->value($this->username)->pattern('alpha')->required()  ? $this->username : null;
        $email = $val->name('email')->value($this->email)->pattern('email')->required()  ? $this->email : null;
        $pass = $val->name('password')->value($this->password)->customPattern('[A-Za-z0-9-.;_!#@]{5,15}')->required()  ? $this->password : null;
        $token = bin2hex(random_bytes(50));
        $urlVerify = $_SERVER['SERVER_NAME'].'/verify_email?token='.$token;

        if ($val->isSuccess()) {

            $sql = "INSERT INTO users 
                    (username, email, password, token) 
                    VALUES 
                    (:username,:email,:password,:token)";
            
            try {
                $hash = password_hash($this->password, PASSWORD_BCRYPT);

                $query = static::getDB()->prepare($sql);
                
                $query->execute([
                    'username'   => $this->username,
                    'email'      => $this->email,
                    'password'   => $hash,
                    'token'      => $token
                ]);

                $sendMail = new Mailer();
                $sendMail->send($email, $urlVerify);
                $this->state = ["<div class='alert-success'>Success</div>"];
                
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            $this->state = $val->errors;
        }
    }
}
