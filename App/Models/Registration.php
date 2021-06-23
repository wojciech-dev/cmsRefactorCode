<?php

namespace App\Models;

use PDO;

/**
 * Register model
 */
class Registration extends \Core\Model {

    private string $email;
    private string $username;
    private string $password;
    private string $token;
    
    public function __construct(array $data) {
        $this->email    = $data['email'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->token    = $data['token'];
    }

    public function rgisterUser(): void {
		
		$sql = "INSERT INTO users 
                (username, email, password, token, created_at) 
                VALUES 
                (:username,:email,:password,:token,:created_at)";
		
        try {
			$hash = password_hash($this->password, PASSWORD_BCRYPT);
            $datetime = date('Y-m-d H:i:s');

			$query = static::getDB()->prepare($sql);
			
            $query->execute([
                'username'   => $this->username,
                'email'      => $this->email,
                'password'   => $hash,
                'created_at' => $datetime,
                'token'      => $this->token
            ]); 
            
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
    }
}
