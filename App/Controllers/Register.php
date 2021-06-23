<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Models\{Select, MenuTree, Registration};
use App\Helpers\{Validation, Mailer};

class Register extends \Core\Controller {

    /**
     * @return void
     */

    public function index(): void {

        
        View::renderTemplate('front/registerForm.html');     
    }
}

