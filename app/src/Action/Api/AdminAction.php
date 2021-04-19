<?php

namespace app\src\Action\Api;

use app\src\Constant\ErrorCode;
use app\src\Factory\AdminFactory;
use app\src\Factory\SekolahFactory;
use app\src\Factory\TahunAjaranFactory;
use app\src\Utility\TokenParser;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\StatusCode;

final class AdminAction
{

    private $adminFactory;
    private $sekolahFactory;
    private $tahunAjaranFactory;

    public function __construct()
    {
        $this->adminFactory = new AdminFactory;
        $this->sekolahFactory = new SekolahFactory;
        $this->tahunAjaranFactory = new TahunAjaranFactory;
    }

  // ADMIN USER START
    /**
     * POST mapping
     * /service/login
     */
    public function login(Request $request, Response $response, $args)
    {
        $admin_map = [
            'username' => '',
            'password' => ''
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($admin_map[$key])) {
                $admin_map[$key] = $val;
            }
        }

        $admin = $this->adminFactory->login($admin_map['username'], $admin_map['password']);

        if ($admin['status']) {
            $key = 'admin';
            $parser = new TokenParser();
            $admin['access_token'] = $parser->generateAccessAPI(
                $admin['data'], $key
            );
        }

        return $response->withJson(
            $admin, 
            StatusCode::HTTP_OK
        );
    }

  // ADMIN USER END


  // SEKOLAH START

    /**
     * GET mapping
     * /service/sekolah
     * /service/sekolah/{id}
     */
    public function sekolah(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');

        $sekolah_id = $args['id'];

        return $response->withJson(
            $this->sekolahFactory->getActiveSekolah($admin->id, $sekolah_id), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * post mapping
     * /service/sekolah
     */
    public function createSekolah(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');
        $sekolah_map = [
            'name' => '',
            'NPSN' => '',
            // 'administrator_id' => '',
            'email' => '',
            'phone_number' => '',
            'address' => '',
            'image_url' => '',
            'status' => '',
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($sekolah_map[$key])) {
                $sekolah_map[$key] = $val;
            }
        }

        // cek admin have existing sekolah
        $exist = $this->sekolahFactory->getActiveSekolah($admin->id);
        if ($exist['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Admin hanya diperbolehkan memiliki 1 sekolah',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }
        
        return $response->withJson(
            $this->sekolahFactory->create($admin->id, $sekolah_map), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * put mapping
     * /service/sekolah/{id}
     */
    public function updateSekolah(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');
        $sekolah_map = [
            // 'id' => '',
            'name' => '',
            // 'NPSN' => '',
            // 'administrator_id' => '',
            'email' => '',
            'phone_number' => '',
            'address' => '',
            'image_url' => '',
            // 'status' => '',
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($sekolah_map[$key])) {
                $sekolah_map[$key] = $val;
            }
        }

        $sekolah_map['id'] = $args['id'];

        // cek admin have existing sekolah
        $exist = $this->sekolahFactory->getActiveSekolah($admin->id, $sekolah_map['id']);
        if (!$exist['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Sekolah tidak ditemukan pada user admin ini',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }
        
        return $response->withJson(
            $this->sekolahFactory->update($admin->id, $sekolah_map), 
            StatusCode::HTTP_OK
        );
    }

  // SEKOLAH END

  // TAHUN AJARAN START

    /**
     * GET mapping
     * /service/tahun_ajaran/{sekolah_id}
     * /service/tahun_ajaran/{sekolah_id}/{id}
     */
    public function tahunAjaran(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');

        $sekolah_id = $args['sekolah_id'];
        $tahun_ajaran_id = $args['id'];

        $sekolah = $this->sekolahFactory->getActiveSekolah($admin->id, $sekolah_id);

        if (!$sekolah['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Sekolah tidak ditemukan pada user admin ini',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }

        $NPSN = $sekolah['data'][0]->NPSN;

        $filters = array(
            "per_page" => 10,
            "page" => 1, 
            "order" => "desc",
        );

        $allowed_order = array('ASC','DESC');
        // filtering query params
        foreach ($request->getQueryParams() as $k => $val) {
            if (isset($filters[$k])) {
                if ($k == 'order' && !in_array(strtoupper($val), $allowed_order)) {
                    continue;
                }
                $filters[$k] = $val;
            }
        }

        // pagination
        return $response->withJson(
            $this->tahunAjaranFactory->getTahunAjaran(
                $sekolah_id, $NPSN, $tahun_ajaran_id
            ), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * post mapping
     * /service/tahun_ajaran/{sekolah_id}
     */
    public function createTahunAjaran(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');
        $map = [
            'year' => '',
            'description' => '',
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($map[$key])) {
                $map[$key] = $val;
            }
        }

        $sekolah_id = $args['sekolah_id'];
        $sekolah = $this->sekolahFactory->getActiveSekolah($admin->id, $sekolah_id);

        if (!$sekolah['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Sekolah tidak ditemukan pada user admin ini',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }
        
        return $response->withJson(
            $this->tahunAjaranFactory->create($admin->id, $sekolah['data'][0], $map), 
            StatusCode::HTTP_OK
        );
    }

    /**
     * put mapping
     * /service/tahun_ajaran/{sekolah_id}/{id}
     */
    public function updateTahunAjaran(Request $request, Response $response, $args)
    {
        $admin = $request->getAttribute('master');
        $map = [
            'year' => '',
            'description' => '',
            'status' => 1,
        ];

        foreach ($request->getParsedBody() as $key => $val) {
            if (isset($map[$key])) {
                $map[$key] = $val;
            }
        }

        $sekolah_id = $args['sekolah_id'];
        $tahun_ajaran_id = $args['id'];

        $sekolah = $this->sekolahFactory->getActiveSekolah($admin->id, $sekolah_id);

        if (!$sekolah['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Sekolah tidak ditemukan pada user admin ini',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }
        $sekolah = $sekolah['data'][0];

        $tahun_ajar = $this->tahunAjaranFactory->getTahunAjaran(
            $sekolah_id, $sekolah->NPSN, $tahun_ajaran_id
        );
        if (!$tahun_ajar['status']) {
            return $response->withJson([
                    'status' => false,
                    'msg' => 'Tahun Ajaran tidak ditemukan pada user admin ini',
                    'code' => ErrorCode::CODE_NOT_ALLOWED
                ], 
                StatusCode::HTTP_LOCKED
            );
        }
        
        return $response->withJson(
            $this->tahunAjaranFactory->update($admin->id, $sekolah, $tahun_ajaran_id, $map), 
            StatusCode::HTTP_OK
        );
    }

  // TAHUN AJARAN END
}
