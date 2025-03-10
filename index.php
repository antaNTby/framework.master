<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';
require_once 'etc/config.php';

use App\Controller\DefaultController;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$route  = new Route('/', ['_controller' => DefaultController::class, '_method' => 'index']);
$route1 = new Route('/about', ['_controller' => DefaultController::class, '_method' => 'about']);

$routes = new RouteCollection();
$routes->add('default', $route);
$routes->add('about', $route1);

dump($routes);

$context = new RequestContext();

$matcher = new UrlMatcher($routes, $context);

$parameters = $matcher->match(strtok($_SERVER['REQUEST_URI'], '?'));

$classname = $parameters['_controller'];
$method    = $parameters['_method'];

$request = Request::createFromGlobals();

$container = new DI\Container([
    Connection::class  => function () {
        $connectionParams = [
            'dbname'   => DB_NAME,
            'user'     => DB_USER,
            'password' => DB_PASSWORD,
            'host'     => DB_HOSTNAME,
            'driver'   => 'pdo_mysql',
        ];
        return DriverManager::getConnection($connectionParams);
    },
    Environment::class => function () {
        $loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . '/templates');
        return new Environment($loader, [
            //'cache' => $_SERVER['DOCUMENT_ROOT'] . '/cache',
        ]);
    },
]);
$controller = $container->get(DefaultController::class);
$response   = $controller->$method($request);
$response->send();
