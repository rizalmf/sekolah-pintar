<?php

namespace app\src\Action;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\StatusCode;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="e-menu-utilities")
 * @OA\Server(url="http://localhost/sekolah-pintar", description="development")
 * @OA\Server(url="https://sekolah.rizalmaulanaf.masuk.web.id", description="production")
 */
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
