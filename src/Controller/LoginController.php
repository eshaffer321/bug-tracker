<?php
/**
 * Created by PhpStorm.
 * User: Erick Shaffer
 * Date: 11/26/17
 * Time: 2:44 PM
 */

namespace App\Controller;
require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
use Psr\Container\ContainerInterface;

/**
 * @property ContainerInterface container
 */
class LoginController{

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    function login($request, $response, $args){
        $body = $request->getParsedBody();
        if ($body['username'] != "" && $body['password'] != "") {
            $userController = $this->container['UserController'];
            return $userController->loginUser($body['username'], $body['password'], $response);
        }
        return $response->withStatus(500)->write("Please enter username and password");
    }

    function logout($request, $response, $args){
        $user_controller = $this->container['UserController'];
        return $user_controller->logoutUser($request, $response, $args);
    }
}
