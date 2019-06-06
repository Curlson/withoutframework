<?php
namespace AExample;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;

class Hello
{
    private $foo;
    
    private $response;

    // string $foo
    public function __construct(
        string $foo,
        ResponseInterface $response
    ){
        $this->foo = $foo;
        $this->response = $response;
    }
    

    public function hello(ServerRequest $request)
    {
        $name = $request->getAttribute('name');
        return "Hello {$name}- autoload by PSR-4";
    }

    public function __toString()
    {
        'Hello class';
    }

    public function __invoke(): ResponseInterface
    {
        $response = $this->response
            ->withHeader('Content-Type', 'text/htmo');
        $response->getBody()
        ->write("<html><head><body>Hello, {$this->foo} World!</body></head></html>");
        return $response;
    }
}
