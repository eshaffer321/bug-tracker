<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/Autoload.php";

$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__ . "/src/Models/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'url' => 'mysql://admin:xceCjUmWLvzSXNqZLyrHXs45@bug-tracker.cjzyegeqvbre.us-west-2.rds.amazonaws.com/bt',
);

// obtaining the entity manager
$em = EntityManager::create($conn, $config);

function getEntityManager(){
    global $em;
    return $em;
}

function getDatabaseConnection(){
    $dbHost = "bug-tracker.cjzyegeqvbre.us-west-2.rds.amazonaws.com";
    $dbPort = 3306;
    $dbName = "bt";
    $username = "admin";
    $password = "xceCjUmWLvzSXNqZLyrHXs45";
    $dbConn = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName", $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbConn;
}