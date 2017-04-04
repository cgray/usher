<?php
namespace Usher;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher as RouteDispatcher;
use Psr\Http\Message\ServerRequestInterface as Request;
use Usher\FileNotFoundException;
use Usher\MethodNotImplementedException;

class Router
{
    private $basePath = '';
    private $routes = [];
    
    public function __construct(string $basePath, array $routes = [])
    {
        $this->basePath = rtrim($basePath, "/");
        $this->setRoutes(...$routes);
    }
    
    private function setRoutes(Route ...$routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }
    
    public function addRoute(Route $route)
    {
        $this->routes[$route->getName()] = $route;
    }
    
    public function getRoute(string $name) : Route
    {
        if (!isset($this->routes[$name])) {
            throw new RouteNotFoundException($name);
        }
        return $this->routes[$name];
    }
    
    public function route(Request $request) : Request
    {
        $dispatcher = $this->getRouteDispatcher();
        $resp = $dispatcher->dispatch($request->getMethod(), $request->getRequestTarget());
        $status = array_shift($resp);
        switch($status){
            case RouteDispatcher::NOT_FOUND:
                throw new FileNotFoundException();
                break;
            case RouteDispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotImplementedException(array_shift($resp));
                break;
            case RouteDispatcher::FOUND:
                list($route, $parameters) = $resp;
                $request = $request->withAttribute('matched_route', $route);
                $parameters = $parameters + $route->getParams();

                foreach ($parameters as $key => $value) {
                    $request = $request->withAttribute($key, $value);
                }
                return $request;
                break;
        }
    }
    
    private function getRouteDispatcher() : RouteDispatcher
    {
        return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach($this->routes as $route) {
                $r->addRoute($route->getMethod(), $this->basePath.$route->getUri(), $route);
            }
        });       
    }
}