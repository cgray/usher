<?php

namespace Usher;

use RuntimeException;

class MethodNotImplementedException extends RuntimeException {
    private $supportedMethods;
    
    public function __construct(array $supported_methods)
    {
        $this->suppoertedMethods = $supported_methods;    
    }
    
    public function getSupportedMethods()
    {
        return $this->supportedMethods;
    }
}