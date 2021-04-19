<?php

namespace app\src\Action\Api;

use app\src\Constant\ErrorCode;
use app\src\Factory\CsFactory;
use app\src\Object\Antrian;
use app\src\Utility\TokenParser;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

final class CsAction
{

    private $csFactory;
    private $antrian;

    public function __construct()
    {
        $this->csFactory = new CsFactory;
        $this->antrian = new Antrian;
    }

    /**
     * POST mapping
     * /service/login
     */
    public function login(Request $request, Response $response, $args)
    {
        $cs_map = [
            'username' => '',
            'password' => ''
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($cs_map[$key])) {
                $cs_map[$key] = $val;
            }
        }

        $get = $this->csFactory->loginAndLog($cs_map['username'], $cs_map['password']);

        if ($get['status']) {
            $parser = new TokenParser();
            $get['access_token'] = $parser->generateAccessAPI($get['data']);
        }

        return $response->withJson($get, StatusCode::HTTP_OK);
    }

    /**
     * POST mapping
     * /service/call
     */
    public function call(Request $request, Response $response, $args)
    {
        $call_map = [
            'id_cs' => '',
            'id_layanan' => '',
            'id_loket' => ''
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($call_map[$key])) {
                $call_map[$key] = $val;
            }
        }

        $cs = $request->getAttribute('cs');

        if (empty($call_map['id_cs'])
            || empty($call_map['id_layanan'])
            || empty($call_map['id_loket'])
            || $call_map['id_cs'] != $cs['id_cs']
            ) {
            return $response->withJson(array(
                'status' => false,
                'msg' => 'unsyncronize param',
                'code' => ErrorCode::CODE_BAD_REQUEST
            ), StatusCode::HTTP_LOCKED);
        }

        $call = $this->csFactory->callAntrianAndLog(
            $cs, 
            $call_map['id_layanan'], 
            $call_map['id_loket'], 
            $this->antrian->status['active']
        );

        return $response->withJson($call, StatusCode::HTTP_OK);
    }
    
    /**
     * POST mapping
     * /service/recall
     */
    public function recall(Request $request, Response $response, $args)
    {
        $call_map = [
            'id_antrian' => ''
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($call_map[$key])) {
                $call_map[$key] = $val;
            }
        }

        $cs = $request->getAttribute('cs');

        return $response->withJson(
            $this->csFactory->recallAntrianAndLog(
                $cs, 
                $call_map['id_antrian']
            ), StatusCode::HTTP_OK
        );
    }

    /**
     * POST mapping
     * /service/skip
     */
    public function skip(Request $request, Response $response, $args)
    {
        $call_map = [
            'id_antrian' => ''
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($call_map[$key])) {
                $call_map[$key] = $val;
            }
        }

        $cs = $request->getAttribute('cs');

        return $response->withJson(
            $this->csFactory->recallAntrianAndLog(
                $cs, 
                $call_map['id_antrian']
            ), StatusCode::HTTP_OK
        );
    }
}
