<?php
namespace App\Controller;
require_once $_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php";
require_once $_SERVER['DOCUMENT_ROOT']."/bootstrap.php";

use PDOException;
use Psr\Container\ContainerInterface;
use App\Models\Product;
class ProductController
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    function createProduct($productName)
    {
        $em = getEntityManager();
        $product = new Product();
        $product->setName($productName);

        $em->persist($product);
        $em->flush();

        echo "Created Product with ID " . $product->getId() . "\n";
    }

    function getAllProducts($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $sql = "SELECT * FROM `products`";
        $stmt = $db -> prepare ($sql);
        $stmt -> execute ();
        $data = array();
        while($row = $stmt ->fetch()) {
            $entry = array();
            $entry['id'] = $row['id'];
            $entry['name'] = $row['name'];
            $entry['price'] = $row['price'];
            $entry['status'] = $row['status'];
            $entry['timestamp'] = $row['timestamp'];
            array_push($data, $entry);
        }
        return $response->withJson($data);
    }

    function priceFilter($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM `products` AS p WHERE p.price LIKE '%" . $body['price'] . "%'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['name'] = $row['name'];
                $entry['price'] = $row['price'];
                $entry['timestamp'] = $row['timestamp'];
                $entry['status'] = $row['status'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch(PDOException $e){
            return $response()->withStatus(500)->write($e);
        }
    }

    function nameFilter($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM `products` AS p WHERE p.name LIKE '%" . $body['name'] . "%'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['name'] = $row['name'];
                $entry['price'] = $row['price'];
                $entry['timestamp'] = $row['timestamp'];
                $entry['status'] = $row['status'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch(\PDOException $e){
            return $response()->withStatus(500)->write($e);
        }
    }

    function availableFilter($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM `products` AS p WHERE p.status = 'YES'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['name'] = $row['name'];
                $entry['price'] = $row['price'];
                $entry['timestamp'] = $row['timestamp'];
                $entry['status'] = $row['status'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch(PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function unavailableFilter($request, $response, $args)
    {
        $db = getDatabaseConnection();
        $body = $request->getParsedBody();
        $sql = "SELECT * FROM `products` AS p WHERE p.status = 'NO'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $entry = array();
                $entry['name'] = $row['name'];
                $entry['price'] = $row['price'];
                $entry['timestamp'] = $row['timestamp'];
                $entry['status'] = $row['status'];
                array_push($data, $entry);
            }
            return $response->withJson($data);
        } catch(PDOException $e) {
            return $response->withStatus(500)->write($e);
        }
    }

    function generateReport($request, $response, $args)
    {
        return $response->withJson(
            array(
                'average_price' => $this->averageProductPrice(),
                'total_products' => $this->getTotalProducts(),
                'total_available' => $this->getTotalAvailable(),
                'total_unavailable' => $this->getTotalUnavailable()
            )
        );
    }

    function averageProductPrice()
    {
        $db = getDatabaseConnection();
        $sql ="SELECT AVG(price) AS average FROM `products`";
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while($row = $stmt->fetch()){
                $data['average'] = $row['average'];
            }
            return $data;
        }catch(\PDOException $e){
            return $e;
        }
    }

    function getTotalProducts()
    {
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM `products`";
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt ->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(\PDOException $e){
            return $e;
        }
    }

    function getTotalAvailable()
    {
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM `products` AS p WHERE p.status = 'YES'";
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt ->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(\PDOException $e){
            return $e;
        }
    }

    function getTotalUnavailable()
    {
        $db = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM `products` AS p WHERE p.status = 'NO'";
        try{
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $data = array();
            while ($row = $stmt ->fetch()) {
                $data['total'] = $row['total'];
            }
            return $data;
        } catch(\PDOException $e){
            return $e;
        }
    }
}








