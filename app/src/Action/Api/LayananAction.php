<?php

namespace app\src\Action\Api;

use app\src\Factory\LayananFactory;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

final class LayananAction
{

    private $layananFactory;

    public function __construct()
    {
        $this->layananFactory = new LayananFactory;
    }

    /**
     * GET mapping 
     * /service/get_layanan_by_loket
     */
    public function getLayananByLoket(Request $request, Response $response, $args)
    {
        $id_loket = $request->getQueryParam('id_loket');

        return $response->withJson(
            $this->layananFactory->getLayanan($id_loket), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * GET mapping 
     * /service/get_layanan
     */
    public function getLayananWithKategory(Request $request, Response $response, $args)
    {
        return $response->withJson(
            $this->layananFactory->getLayanan(null, true), 
            StatusCode::HTTP_OK
        );
    }
}
