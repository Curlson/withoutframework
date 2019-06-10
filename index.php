<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use AExample\Hello;
use Relay\Relay;
use Zend\Diactoros\ServerRequestFactory;
use function DI\create;
use function DI\get;

use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use function FastRoute\simpleDispatcher;

use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

// 引入 composer 的自动加载类
require_once(__DIR__ . '/vendor/autoload.php');


// import DI\ContainerBuilder and init it.
$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions([
    Hello::class => create(Hello::class)
        ->constructor(get('Foo'), get('Response')),
        'Foo' => 'bar',
    'Response' => function() {
        return new Response();
    },
]);

// 创建容器
$container = $containerBuilder->build();

// routes for the application
$routes = simpleDispatcher(function( RouteCollector $r){
    // routes add here!
    $r->get('/hello/{name}', [Hello::class, 'hello']);
    $r->get('/', function(){
        return "Hello World";
    });
});

$middlewareQueue[] = new FastRoute($routes);

// DI auto add argument to __construct
$middlewareQueue[] = new RequestHandler($container);



// add middlewares queue
// $middlewareQueue = []; // 第二部的初始化， 第三步要去掉。
$requestHandler = new Relay($middlewareQueue);

// merge all info what's necessary for create the new request.
// and pass it to the Relay class.
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());

// 發射器
$emitter = new SapiEmitter();
return $emitter->emit($response);

// $helloWorld = $container->get(Hello::class);
// $words = $helloWorld->hello();
// dd($words);




?>
