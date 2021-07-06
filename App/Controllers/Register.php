<?php

namespace App\Controllers;

use \Core\{View, Router};
use App\Models\{Registration, Select, MenuTree};
use App\Helpers\{Mailer};

class Register extends \Core\Controller {

    /**
     * @return void
     */

    public function index($request): void {

        $getMenu = new Select('menu');

        $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));

        if (isset($_POST['submit'])) {
            $register = new Registration($request->paramsPost());
            $register->save();
        }

        View::renderTemplate('front/registerForm.html', [
            'menu' => $menu,
        ]);

    }
}

