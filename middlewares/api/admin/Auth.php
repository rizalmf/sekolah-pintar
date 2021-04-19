<?php

namespace app\middleware\api\admin;

use app\middleware\core\Core;
use app\src\Constant\ErrorCode;
use app\src\Utility\TokenParser;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Filter incoming jwt
 * 
 */
class Auth extends Core
{
    /**
     * @Override
     */
    protected $whiteList = array(
        'GET_AdminAction:test',
    );

    public function __invoke(Request $request, Response $response, $next)
    {
        if (parent::__invoke($request, $response, $next)) {
            return $next($request, $response);
        } else {
            // return $next($request, $response);
            $jwt = $request->getHeaderLine('Authorization');

            $parser = new TokenParser;
            $key = 'admin';
            $auth = $parser->parseAccessAPI($jwt, $key);

            $msg = 'Unauthorize jwt';
            $code = ErrorCode::CODE_WRONG_TOKEN;
            $http_code = StatusCode::HTTP_UNAUTHORIZED;

            if (isset($auth->scope) && isset($auth->dt)) {
                $dt = strtotime(date('Y-m-d'));
                if ($auth->dt == $dt) {
                    $request = $request->withAttributes(array(
                        'master' => $auth->scope,
                        'auth' => $jwt
                    ));

                    return $next($request, $response);
                } else {
                    $msg = 'Expired';
                    $code = ErrorCode::CODE_EXPIRED;       
                    $http_code = StatusCode::HTTP_LOCKED;
                }
            }

            return $response->withJson(array(
                'status' => false,
                'msg' => $msg,
                'code' => $code
            ), $http_code);
        }
    }
}