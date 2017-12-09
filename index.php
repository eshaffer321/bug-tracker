<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require 'vendor/autoload.php';
include 'bootstrap.php';
session_start();
$auth = $_SESSION['auth'];

$config = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true
]];
$app = new \Slim\App($config);

$loader = new Twig_Loader_Filesystem('src/Views/');
$twig = new Twig_Environment($loader);

$container = new \Slim\Container;
$container = $app->getContainer();

$container['em'] = function(){
    return getEntityManager();
};

$container['LoginController'] = function ($container) {
    return new App\Controller\LoginController($container);
};

$container['UserController'] = function ($container) {
    return new App\Controller\UserController($container);
};

$container['SignupController'] = function ($container) {
    return new App\Controller\SignupController($container);
};

$container['ProductController'] = function ($container) {
    return new App\Controller\ProductController($container);
};

$container['BugController'] = function ($container) {
    return new App\Controller\BugController($container);
};

$container['twig'] = function ($container) {
    $loader = new Twig_Loader_Filesystem('src/Views/');
    return new Twig_Environment($loader);
};

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$app->get('/', function () use($twig) {
    global $auth;
    if ($auth) {
        echo $twig->render('bugs.html.twig');
    } else {
        echo $twig->render('signup.html.twig');
    }
});

$app->get('/bugs', function() use($twig) {
    global $auth;
    if ($auth) {
        echo $twig->render('bugs.html.twig');
    } else {
        echo $twig->render('signup.html.twig');
    }
});

$app->get('/users', function() use($twig) {
    global $auth;
    if ($auth) {
        echo $twig->render('user.html.twig');
    } else {
        echo $twig->render('signup.html.twig');
    }
});

$app->get('/reports', function() use($twig) {
    echo $twig->render('reports.html.twig');
});

$app->get('/products', function() use($twig) {
    echo $twig->render('products.html.twig', array('auth' => $_SESSION['auth']));
});

$app->get("/logout", 'LoginController:logout');

$app->post('/login', 'LoginController:login');

$app->post('/users/engineer', 'UserController:getAllEngineers');

$app->post('/users/reporters', 'UserController:getAllReporters');

$app->post('/users/all', 'UserController:getAllUsers');

$app->post('/users/update', 'UserController:updateUser');

$app->post('/users/delete', 'UserController:deleteUser');

$app->post("/products/all", 'ProductController:getAllProducts');

$app->post("/products/name", 'ProductController:nameFilter');

$app->post("/products/available", 'ProductController:availableFilter');

$app->post("/products/unavailable", 'ProductController:unavailableFilter');

$app->post("/products/price", 'ProductController:priceFilter');

$app->post('/bugs/all', 'BugController:getAllBugs');

$app->post('/bugs/create', 'BugController:createBug');

$app->post('/bugs/update', 'BugController:updateBug');

$app->post('/report/bugs', 'BugController:generateReport');

$app->post('/report/user', 'UserController:generateReport');

$app->post('/report/product', 'ProductController:generateReport');

$app->post('/signup', 'SignupController:signup');

$app->run();
