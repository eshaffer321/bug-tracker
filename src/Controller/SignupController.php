<?php
/**
 * Created by PhpStorm.
 * User: erickMac
 * Date: 11/26/17
 * Time: 2:45 PM
 */
namespace App\Controller;
include 'UserController.php';
use App\Controller\UserController;
use Psr\Container\ContainerInterface;

class SignupController{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    function signup($request, $response, $args){
        $body = $request->getParsedBody();
        if($body['username'] != "" && $body['password'] != "" && $body['role'] != "") {
            $userController = $this->container['UserController'];
            if($userController->userNameAvailable($body['username'])) {
                return $userController->createUser($body['username'], $body['password'], $body['role'], $response);
            }
            return $response->withStatus(500)->write("Username taken. Please try another");
        }
        return $response->withStatus(500)->write("Make sure all fields are entered");
    }
}
