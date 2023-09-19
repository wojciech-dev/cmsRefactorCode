<?php
session_start();

use \Core\View;

$router = new Core\Router();

//front
$router->respondWithController('POST', '/verify_email', 'Front@index');
$router->respondWithController('GET', '/logout', 'Menu@logout');
$router->respondWithController('GET', '/[*:title][i:id]?', 'Front@index');
$router->respondWithController('POST', '/[:title]', 'Front@index');
$router->respondWithController('POST', '/send', 'Send@sendEmail');

//backend
$router->with('/admin', function () use ($router) {
    if (isset($_SESSION['username']) && $_SESSION['type'] == 'admin') {
        $router->respondWithController(['GET', 'POST'], '/menu/[create|edit|delete:action]?/[:id]?', 'Menu@index');
        $router->respondWithController(['GET', 'POST'], '/[body|banner|box:section]/[create|edit|delete:action]?/[:id]?', 'Body@index');
        $router->respondWithController(['GET', 'POST'], '/remove/[:field]', 'DeletePhoto@remove');
        $router->respondWithController(['GET', 'POST'], '/sortlist', 'Body@sortList');
    } else if (isset($_SESSION['username']) && $_SESSION['type'] == 'user') {
        $router->respondWithController(['GET', 'POST'], '/users', 'User@index');
        $router->respondWithController(['GET', 'POST'], '/users/account', 'User@account');
    }
});

//404 and other
$router->onHttpError(function ($code, $router) {
    switch ($code) {
        case 404:
            $router->response()->body(
                View::renderTemplate('404.html')
            );
            break;
        case 405:
            $router->response()->body(
                'You can\'t do that!'
            );
            break;
        default:
            $router->response()->body(
                'Oh no, a bad error happened that caused a ' . $code
            );
    }
});

// Dispatch the router
$router->dispatch();
