<?php
declare(strict_types=1);

namespace App\Controllers;

use \Core\{View};
use App\Helpers\{Validation};
use App\Models\{LoginModel, Select, MenuTree};

class Login extends \Core\Controller {

    /**
     * @return void
     */

    public function index($request): void {

        if (isset($_POST['submit'])) {
            $get = new LoginModel($request->paramsPost());
            $get->loginUser();
        }
            
        View::renderTemplate('front/loginForm.html');
    }
}

