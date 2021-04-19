<?php

namespace app\middleware\core;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class Core
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $route = $request->getAttribute('route');

        return ($route && $this->isWhiteList($route));
    }


    /**
     * Prevent specific route
     * 
     * @param Slim\Route $route
     * @return boolean
     */
    protected function isWhiteList($route)
    {
        $callable = substr($route->getCallable(), strrpos($route->getCallable(), '\\')+1);
        $method = strtoupper($route->getMethods()[0]);

        return in_array($method.'_'.$callable, 
            $this->whiteList
        );
    }

    /**
     * List callable yang di whitelist. 
     * callable disamakan dari route yang didaftarkan di /routes/routes.php
     * 
     * format: METHOD.'_'.callable
     * 
     */
    protected $whiteList = array(
        'GET_HomeAction:test',
    );
}