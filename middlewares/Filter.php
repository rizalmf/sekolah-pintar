<?php

namespace app\middleware;

use app\middleware\core\Core;
use app\src\Utility\XssGuard;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Filter all incoming args, query params & body
 * 
 */
class Filter extends Core
{
    /**
     * @Override
     */
    protected $whiteList = array(
        'GET_HomeAction:test',
    );
    
    public function __invoke(Request $request, Response $response, $next)
    {
        if (parent::__invoke($request, $response, $next)) {
            return $next($request, $response);
        } else {
            $route = $request->getAttribute('route');
            if ($route) {
                // xss filter
                $guard = new XssGuard();

                // filter arguments
                if ($route->getArguments() != null) {
                    $request = $request->withAttribute('route', 
                        $route->setArguments(
                            $guard->clean($route->getArguments())
                        )
                    );
                }

                // filter query params & body
                $request = $request->withQueryParams(
                        $guard->clean($request->getQueryParams())
                    )->withParsedBody(
                        $guard->clean($request->getParsedBody()
                    )
                );
            }

            return $next($request, $response);
        }     
    }
}