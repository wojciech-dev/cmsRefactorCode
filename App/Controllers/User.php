<?php

namespace App\Controllers;

use \Core\{View};
use App\Helpers\{Functions, Posts};
use App\Models\{Select, Add, Delete, Update, MenuTree};

class User extends \Core\Controller
{
    /*
   * User content
   * @return void
   */
    public function index($request, $response, $service, $app): void
    {

        View::renderTemplate('admin/user/user.html', [
            'username' => $_SESSION['username']
        ]);
    }

    public function account($request, $response, $service, $app): void
    {
        $section = 'users';
        $section_post = $section . '_post';
        $getUser = new Select("users");
        $update = $getUser->getRows(['where' => ['username' => $_SESSION['username']], 'type' => 'fetch']);

        if (isset($_POST['submit'])) {
            $edit = new Update(Posts::$section_post($request->paramsPost()), $section);
            $edit->edit($update['id']);
            Functions::redirect("/admin/$section");
        }

        View::renderTemplate('admin/user/userForm.html', [
            'username' => $_SESSION['username'],
            'item' => $update,
        ]);
    }
}
