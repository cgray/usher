<?php
namespace Usher\Test;

use Usher\Route;
use Usher\Router;
use Usher\HttpRoute;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testAddGetRoute()
    {
        $router = new Router('');
        $route = new Route('test', 'GET', '/test', 'test_target');
        $router->addRoute($route);
        $testroute = $router->getRoute('test');
        $this->assertSame($route, $testroute);
    }

    public function testFoundNoBaseUrl()
    {
        $request = $this->createMock('\Psr\Http\Message\ServerRequestInterface');
        $request->method('getRequestTarget')->willReturn('/path/to/resource');
        $request->method('getMethod')->willReturn('GET');
        $request->expects($this->exactly(3))->method('withAttribute')->willReturn($request);
        $router = new Router('', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $request = $router->route($request);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $request);
    }

    public function testFoundWithBaseUrl()
    {
        $request = $this->createMock('\Psr\Http\Message\ServerRequestInterface');
        $request->method('getRequestTarget')->willReturn('/my/base-url/path/to/resource');
        $request->method('getMethod')->willReturn('GET');
        $request->expects($this->exactly(3))->method('withAttribute')->willReturn($request);
        $router = new Router('/my/base-url/', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $request = $router->route($request);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $request);
    }
    
    /**
     * @expectedException \Usher\FileNotFoundException
     */
    public function testNotFound()
    {
        $request = $this->createMock('\Psr\Http\Message\ServerRequestInterface');
        $request->method('getRequestTarget')->willReturn('/my/missing/path/to/resource');
        $request->method('getMethod')->willReturn('GET');
        $request->method('withAttribute')->willReturn($request);
        $router = new Router('/my/base-url/', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $request = $router->route($request);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $request);
    }    

    /**
     * @expectedException \Usher\MethodNotImplementedException
     */
    public function testMethodNotAllowed()
    {
        $request = $this->createMock('\Psr\Http\Message\ServerRequestInterface');
        $request->method('getRequestTarget')->willReturn('/my/base-url/path/to/resource');
        $request->method('getMethod')->willReturn('POST');
        $request->method('withAttribute')->willReturn($request);
        $router = new Router('/my/base-url/', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $request = $router->route($request);
        $this->assertInstanceOf('\Psr\Http\Message\ServerRequestInterface', $request);
    }
    
    /**
     * @expectedException \Usher\RouteNotFoundException
     */
    public function testGetBadRoute()
    {
        $router = new Router('/my/base-url/', [new Route('test','GET','/path/to/{resource}','target',['foo'=>'bar'])]);
        $router->getRoute('not-here');    
    }
}