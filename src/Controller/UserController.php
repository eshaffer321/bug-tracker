<?php

namespace App\Controller;
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bootstrap.php";

use PDOException;
use Psr\Container\ContainerInterface;
use App\Models\User;

class UserController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    function createUser($username, $password, $role, $response)
    {
        $db = getDatabaseConnection();
        $sql = "INSERT INTO `users` (username, password, role)
                VALUES (:username, :pw, :role)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(array(
                ":username" => $username,
                ":pw" => $this->hashPassword($password),
                ":role" => $role
            ));
            return $response->withStatus(200)->write("Successfully created user " . $username);
        } catch (PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function updateUser($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "UPDATE `users` as u
                SET username = :username, role = :role, authenticated = :signed_in
                WHERE u.id= :id";
        try {
            $stmt = $db -> prepare ($sql);
            $stmt -> execute (array(
                ":id" => $body['id'],
                ":username" => $body['username'],
                ":role" => $body['role'],
                ":signed_in" => $body['signed_in']
            ));
            return $response->withStatus(200)->write('Successfully updated user: ' . $body['username']);
        } catch (PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function getAllUsers($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT * FROM `users`";
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['id'] = $row['id'];
                $entry['username'] = $row['username'];
                $entry['role'] = $row['role'];
                $entry['signed_in'] = $row['authenticated'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch (PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function getAllEngineers($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT * FROM `users` AS u WHERE
                    u.role = 'Engineer'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['id'] = $row['id'];
                $entry['username'] = $row['username'];
                $entry['role'] = $row['role'];
                $entry['signed_in'] = $row['authenticated'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch (PDOException $e){
            return $response->withStatus(500)->write($e);
        }
    }

    function getAllReporters($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT * FROM `users` WHERE role = 'Reporter'";
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['id'] = $row['id'];
                $entry['username'] = $row['username'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch (PDOException $e) {
            echo $response->withStatus(500)->write($e->getMessage());
        }
        return $response->withStatus(500)->write("Something went wrong...");
    }

    function loginUser($username, $password, $response)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT password FROM `users` AS u 
                WHERE u.username = :username";
        try {
            $stmt = $db -> prepare ($sql);
            $stmt -> execute (array(
                ":username" => $username
            ));
            $pw = "";
            while ($row = $stmt->fetch()) {
                $pw = $row['password'];
            }
            if (password_verify($password, $pw)) {
                $sql = "UPDATE `users` as u
                SET authenticated = 1
                WHERE u.username= :username";
                $stmt = $db -> prepare ($sql);
                $stmt -> execute (array(":username" => $username));
                session_start();
                $_SESSION['auth'] = true;
                $_SESSION['username'] = $username;
                return $response->withStatus(200)->write("Welcome " . $username);
            }
            return $response->withStatus(500)->write("Invalid login");
        } catch (PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function logoutUser($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "UPDATE `users` as u
                SET authenticated = 0
                WHERE u.username= :username";
        try{
            $stmt = $db -> prepare ($sql);
            $stmt -> execute (array(
                ":username" => $_SESSION['username']
            ));
            $_SESSION['auth'] = false;
            $_SESSION['username'] = "";
            $_SESSION = [];
            session_unset();
            session_destroy();
            return $response->withStatus(200)->write('Successful logout');
        }catch(PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function userNameAvailable($username)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(*) FROM `users` AS u WHERE u.username = :username";
        $stmt = $db->prepare($sql);
        $stmt ->execute(array(":username" => $username));
        $data = $stmt->fetch();
        if ($data[0] == 0) {
            return true;
        }
        return false;
    }

    function deleteUser($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        try {
            $sql = "DELETE FROM `users` WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(
                ":id" => $body['id']
            ));
            return $response->withStatus(200)->write("Successfully deleted " . $body['username']);
        } catch(PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function generateReport($request, $response, $args){
        $data = array(
            'eng_count' => $this->getAmountOfEngineers(),
            'reporter_count' => $this->getAmountOfReporters(),
            'eng_most_bugs' => $this->getEngineerWithMostBugs()
        );
        return $response->withJson($data);
    }

    function getAmountOfEngineers(){
        $db = getDatabaseConnection();
        try{
            $sql = "SELECT COUNT(*) AS total FROM `users` WHERE role = 'Engineer'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = "";
            while($row= $stmt->fetch()) {
                $data = $row['total'];
            }
            return $data;
        } catch(PDOException $e){
            return $e;
        }
    }

    function getEngineerWithMostBugs() {
        $db = getDatabaseConnection();
        try {
            $sql = "SELECT username, COUNT(*) AS total
                    FROM `bugs` AS b
                    INNER JOIN `users` AS u ON b.engineer_id = u.id
                    GROUP BY engineer_id
                    ORDER BY COUNT(*) DESC
                    LIMIT 1";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $entry = array();
            while($row= $stmt->fetch()) {
                $entry['username'] = $row['username'];
                $entry['total'] = $row['total'];
                return $entry;
            }
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            return $e;
        }
    }

    function getAmountOfReporters() {
        $db = getDatabaseConnection();
        try{
            $sql = "SELECT COUNT(*) AS total FROM `users` WHERE role = 'Reporter'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                return $row['total'];
            }
            return $stmt->fetchAll();
        } catch(PDOException $e){
            return $e;
        }
    }

    function parseData($users)
    {
        $newArr = array();
        foreach ($users as $u) {
            $inner['username'] = $u->getuserName();
            $inner['id'] = $u->getId();
            array_push($newArr, $inner);
        }
        return $newArr;
    }

    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}