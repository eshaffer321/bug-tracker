<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);

// database configuration parameters
$conn = array(
    'url' => 'mysql://admin:xceCjUmWLvzSXNqZLyrHXs45@bug-tracker.cjzyegeqvbre.us-west-2.rds.amazonaws.com/bt',
);

// obtaining the entity manager
$em = EntityManager::create($conn, $config);
$driver = \Doctrine\DBAL\DriverManager::getConnection($conn, $config);