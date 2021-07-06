<?php
declare(strict_types=1);

namespace App\Controllers;

use \Core\{View};
use App\Models\{LoginModel, Select, MenuTree};

class Login extends \Core\Controller {

    /**
     * @return void
     */

    public function index($request): void {

        $getMenu = new Select('menu');

        $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));

        if (isset($_POST['submit'])) {
            $get = new LoginModel($request->paramsPost());
            $get->save();
        }

        View::renderTemplate('front/loginForm.html', [
            'menu' => $menu,
        ]);
            
    }
}

