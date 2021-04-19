<?php

namespace app\src\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;

final class HomeAction
{

    public function __construct()
    {

    }

    public function __invoke(Request $request, Response $response, $args)
    {   
        $response = $response->withStatus(StatusCode::HTTP_FORBIDDEN)->withJson(null);

        return $response;
    }
}
