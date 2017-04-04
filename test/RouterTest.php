<?php
namespace Usher\Test;

use Usher\Route;
use Usher\Router;
use Usher\HttpRoute;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testAddGetRoute() {
        $router = new Router('');
        $route = new Route('test', 'GET', '/test', 'test_target');
        $router->addRoute($route);
        $testroute = $router->getRoute('test');
        $this->assertSame($route, $testroute);
    }

    public function testFound() {
        $request = $this->createMock('\Psr\Http\Message\ServerRequestInterface');
        $request->method('getRequestTarget')->willReturn('/path/to/resource');
        $request->method('getMethod')->willReturn('GET');
        $request->expects($this->exactly(3))->method('withAttribute')->willReturn($request);
        
        $router = new Router('', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $request = $router->route($request);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $request);
    }
}