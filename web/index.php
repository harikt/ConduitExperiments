<?php
use Aura\Di\ContainerBuilder;
use Conduit\Middleware\RouterMiddleware;
use Conduit\Middleware\AuthenticationMiddleware;
use Phly\Conduit\Middleware;
use Phly\Http\Server;

require dirname(__DIR__) . '/vendor/autoload.php';

Dotenv::load(dirname(__DIR__));

// pre-existing service objects as ['service_name' => $object_instance]
$services = array();

// config classes to call define() and modify() on
$config_classes = array(
    'Aura\Router\_Config\Common',
    'Aura\Auth\_Config\Common',
    'Aura\Session\_Config\Common',
    'Aura\Accept\_Config\Common',
    'FOA\Auth_Session_Bundle\_Config\Common',
    'Skelton\_Config\Common'
);

// use the builder to create a container
$container_builder = new ContainerBuilder;
$di = $container_builder->newInstance(
    $services,
    $config_classes,
    ContainerBuilder::ENABLE_AUTO_RESOLVE
);

require dirname(__DIR__) . '/config/routes.php';
require dirname(__DIR__) . '/config/controllers.php';

$app = new Middleware();
$app->pipe('/admin', $di->get('auth_middleware'));
$app->pipe('/blog/edit', $di->get('auth_middleware'));
$app->pipe('/blog/delete', $di->get('auth_middleware'));
$app->pipe($di->get('negotiation_middleware'));
$app->pipe($di->get('router_middleware'));
$server = Server::createServer($app, $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

$server->listen();
