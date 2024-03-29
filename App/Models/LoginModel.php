<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use App\Helpers\{Validation, Functions};

class LoginModel extends \Core\Model
{

    private string $username;
    private string $password;
    public array $state;

    public function __construct($data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    public function save(): void
    {

        $val = new Validation();

        $user = $val
            ->name('username')
            ->value($this->username)
            ->pattern('alpha') ? $this->username : null;

        $pass = $val
            ->name('password')
            ->value($this->password)
            ->customPattern('[A-Za-z0-9-.;_!#@]{5,15}') ? $this->password : null;

        if ($val->isSuccess()) {

            $sql = "SELECT * FROM users WHERE username = :username";

            try {

                $query = static::getDB()->prepare($sql);
                $query->execute([
                    ':username' => $user
                ]);
                $result = $query->fetch();

                if ($query->rowCount() > 0) {
                    if (password_verify($pass, $result['password'])) {
                        if ($result['verified'] == 1) {
                            $_SESSION['username'] = $result['username'];
                            $_SESSION['type'] = $result['type'];
                            ($result['type'] == 'user') ? Functions::redirect('/admin/users') : Functions::redirect('/admin/menu');
                        } else {
                            $this->state = ["Confirm email address"];
                        }
                    } else {
                        $this->state = ["Login or password incorrect"];
                    }
                } else {
                    $this->state = ["Error", "Enter login and password"];
                }
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            $this->state = $val->errors;
        }
    }
}
