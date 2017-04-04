<?php

namespace Usher;

use RuntimeException;

class Route {
    private $name;
    private $method;
    private $uri;
    private $target;
    private $params = [];
    
    public function __construct(string $name, string $method, string $uri, string $target, array $params = [])
    {
        $this->name = $name;
        $this->method = $method;
        $this->uri = $uri;
        $this->target = $target;
        $this->params = $params;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getUri(): string
    {
        return $this->uri;
    }
    
    public function getTarget(): string
    {
        return $this->target;
    }
    
    public function getParam(string $name, $default = null)
    {
        return $this->params[$name] ?? $default;
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
    public function buildUrl(array $params, $baseUrl = '')
    {
        $uri = $this->uri;
        $matches = [];
        $pattern = '{\s*([a-zA-Z_][a-zA-Z0-9_-]*)\s*(?::\s*([^{}]*(?:\{(?-1)\}[^{}]*)*))?\}';
        $optionalSegments = '\[.*'.$pattern.'\]';
        
        $uri = preg_replace_callback('~'.$optionalSegments.'~x', function($match) use ($params) {
            $uri = trim($match[0], '[]');
            if (isset($params[$match[1]])) {
                return trim($match[0],'[]');
            }
            return '';
        }, $uri);
        
        return $baseUrl.preg_replace_callback('~'.$pattern.'~x', function($match) use ($params) {
            if (!isset($params[$match[1]])) {
                throw new RuntimeException("'".$match[1]."' not defined in input array");
            }
            return $params[$match[1]];
        }, $uri);
    }
}