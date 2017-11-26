<?php
require_once "boostrap.php";
session_start();

function createUser($username, $password, $role)
{
    $user = new User();
    $user->setName($username);
    $user->setPassword(hashPassword($password));
    $user->setRole($role);
    $em->persist($user);
    $em->flush();
    echo "Created User with ID " . $user->getId() . "\n";
}

function loginUser($username, $password)
{
    $q = Doctrine_Query::create()
        ->select('u.password')
        ->from('User u')
        ->where('u.username = $username');

}

function logoutUser()
{

}

function hashPassword($password)
{
    return $password;
}