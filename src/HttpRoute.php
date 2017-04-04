<?php

namespace Usher;

use Usher\Route;

class HttpRoute extends Route
{
    private function __construct(string $name, string $method, string $uri, string $target, array $params = []) {
        parent::__construct($name, $method, $uri, $target, $params);   
    }
    public static function head(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'HEAD', $uri, $target, $params);
    }
    
    public static function get(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'GET', $uri, $target, $params);
    }
    
    public static function post(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'POST', $uri, $target, $params);
    }
    
    public static function options(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'OPTIONS', $uri, $target, $params);
    }
    
    public static function delete(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'DELETE', $uri, $target, $params);
    }
    
    public static function put(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'PUT', $uri, $target, $params);
    }

    public static function patch(string $name, string $uri, string $target, array $params = []) : HttpRoute
    {
        return new self($name, 'PATCH', $uri, $target, $params);
    }
}