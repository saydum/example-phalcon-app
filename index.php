<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require 'vendor/autoload.php';

use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;

$di = new FactoryDefault();

$di->setShared('router', function () {
    $router = new Router();
    $router->add('/', [
        'namespace'  => 'App\Controllers',
        'controller' => 'home',
        'action'     => 'index'
    ]);
    return $router;
});


$di->set(
    'view',
    function () {
        $view = new View();

        $view->setViewsDir(__DIR__ . '/app/Views/');
        $view->registerEngines(
            [
                '.volt' => function (ViewBaseInterface $view) {
                    $volt = new Volt($view, $this);
                    $volt->setOptions(
                        [
                            'always'    => true,
                            'extension' => '.php',
                            'separator' => '_',
                            'stat'      => true,
                            'path'      => __DIR__ . '/cache/',
                            'compiledSeparator' => '_',
                        ]
                    );

                    return $volt;
                }
            ]
        );

        return $view;
    }
);

$di->setShared('response', function () {
    return new Phalcon\Http\Response();
});

$di->setShared('dispatcher', function () {
    $dispatcher = new Phalcon\Mvc\Dispatcher();
    $dispatcher->setDefaultNamespace('App\Controllers');
    return $dispatcher;
});

$app = new Application($di);

try {

    $response = $app->handle($_SERVER['REQUEST_URI']);
    $response->send();
} catch (Exception $e) {
    echo $e->getMessage();
}
