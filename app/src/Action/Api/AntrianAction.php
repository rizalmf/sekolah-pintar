<?php

namespace app\src\Action\Api;

use app\src\Factory\AntrianFactory;
use app\src\Object\Antrian;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

final class AntrianAction
{
    private $antrianFactory;
    private $antrian;

    public function __construct()
    {
        $this->antrianFactory = new AntrianFactory;
        $this->antrian = new Antrian;
    }

    /**
     * GET mapping
     * /service/cek_jumlah
     */
    public function getJumlahAntrian(Request $request, Response $response, $args)
    {
        $id_layanan = $request->getQueryParam('id_layanan');

        return $response->withJson(
            $this->antrianFactory->countAntrian($id_layanan), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * GET mapping
     * /service/get_table_layanan
     */
    public function getAntrianByLayanan(Request $request, Response $response, $args)
    {
        $id_layanan = $request->getQueryParam('id_layanan');
        
        return $response->withJson(
            $this->antrianFactory->getAntrian(
                $id_layanan, 
                $this->antrian->status['active'], 
                true
            ), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * GET mapping
     * /service/get_special_number
     */
    public function getSpecialNumber(Request $request, Response $response, $args)
    {
        $id_layanan = $request->getQueryParam('id_layanan');

        return $response->withJson(
            $this->antrianFactory->getAntrian(
                $id_layanan, 
                $this->antrian->status['skip']
            ), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * POST mapping
     * /service/daftar
     */
    public function daftar(Request $request, Response $response, $args)
    {
        $id_layanan = $request->getParsedBodyParam('id_layanan');

        return $response->withJson(
            $this->antrianFactory->daftarAntrian($id_layanan), 
            StatusCode::HTTP_OK
        );
    }
}
