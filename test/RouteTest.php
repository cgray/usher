<?php

namespace Usher\Test;
use Usher\Route;
use Usher\HttpRoute;
use PHPUnit\Framework\TestCase;


class RouteTest extends TestCase
{
    public function testAccessors(){
        $route = new Route('test-route', 'GET', '/path/to/resource', 'target', ['param1'=>'param1_value']);
        $this->assertSame('test-route', $route->getName());
        $this->assertSame('GET', $route->getMethod());
        $this->assertSame('/path/to/resource', $route->getUri());
        $this->assertSame('target', $route->getTarget());
        $this->assertSame('param1_value', $route->getParam('param1'));
        $this->assertSame('default', $route->getParam('param2','default'));
    }

    public function buildUrlProvider()
    {
        return [
            ['/articles/{id:\d+}[/{name}]', ['id'=>100, 'name'=>'Fred'], '/articles/100/Fred'],
            ['/articles/{id:\d+}[/{name}]', ['id'=>100], '/articles/100']
        ];
    }
    
    /**
     * @dataProvider buildUrlProvider
     */
    public function testBuildUrl($pattern, $params, $url)
    {
        $route = new Route('test','GET', $pattern, 'target');
        $this->assertSame($url, $route->buildUrl($params));
    }
}