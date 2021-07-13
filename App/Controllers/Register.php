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
        $data['token'] = bin2hex(random_bytes(50));
        $urlVerify = $_SERVER['SERVER_NAME'].'/verify_email?token='.$data['token'];
        $menu = MenuTree::buildMenuInFront($getMenu->getRows(['where'=>['status' => 1]]));

        if (isset($_POST['submit'])) {
            $register = new Registration($request->paramsPost());
            $register->save();

            if ($register) {
                $sendMail = new Mailer();
                $sendMail->send($request->param('email'), $urlVerify);
            }
        }

        View::renderTemplate('front/registerForm.html', [
            'menu' => $menu,
        ]);

    }
}

