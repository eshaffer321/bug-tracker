<?php

namespace App\Controller;

use PDOException;
use Psr\Container\ContainerInterface;
use App\Models\Bug;

require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/bootstrap.php";
session_start();

class BugController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    function createBug($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $reporterId = $body['rep_id'];
        $engineerId = $body['eng_id'];
        $productId= $body['product_id'];
        $bugDescription = $body['desc'];
        try {
            $sql = "INSERT INTO `bugs` (reporter_id, engineer_id, product_id, description)
                VALUES (:rep_id, :eng_id, :product_id, :description)";
            $stmt = $db -> prepare ($sql);
            $stmt -> execute (array(
                ":rep_id" => $reporterId,
                ":eng_id" => $engineerId,
                ":product_id" => $productId,
                ":description" => $bugDescription
            ));
        } catch (PDOException $e) {
            echo $response->withStatus(200)->write($e->getMessage());
        }
        return $response->withStatus(200)->write("Bug Sucessfully created");
    }

    function updateBug($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "UPDATE `bugs` as b
                SET b.description = :description, b.status = :status
                WHERE b.id= :id";
        try {
            $stmt = $db -> prepare ($sql);
            $stmt -> execute (array(
                ":id" => $body['data']['id'],
                ":description" => $body['data']['description'],
                ":status" => $body['data']['status'],
            ));
            return $response->withStatus(200)->write('Successfully updated bug: ' .$body['data']['id']);
        } catch (PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function getAllBugs($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT b.id, description, created, b.status, username AS engineer, `name` as product
                FROM `bugs` AS b
                INNER JOIN `users` AS u ON b.engineer_id = u.id 
                INNER JOIN `products` AS p ON b.product_id = p.id";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data = array();
        while ($row = $stmt->fetch()) {
            $entry = array();
            $entry['id'] = $row['id'];
            $entry['description'] = $row['description'];
            $entry['created'] = $row['created'];
            $entry['status'] = $row['status'];
            $entry['engineer'] = $row['engineer'];
            $entry['product'] = $row['product'];
            array_push($data, $entry);
        }
        return $response->withJson($data);
    }

    function generateReport($request, $response, $args){
        return $response->withJson( array(
            'bug_total' => $this->getNumberOfBugs(),
            'open_bugs' => $this->getNumberOfOpenBugs(),
            'closed_bugs' => $this->getNumberOfClosedBugs()
        ));
    }

    function getNumberOfBugs(){
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM `bugs`";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(PDOException $e){
            return $e;
        }
    }

    function getNumberOfOpenBugs(){
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(status) as total FROM `bugs`
                WHERE status = 'OPEN'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(PDOException $e){
            return $e;
        }
    }

    function getNumberOfClosedBugs() {
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(status) as total FROM `bugs`
                WHERE status = 'CLOSED'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(PDOException $e){
            return $e;
        }
    }
}










