<?php

namespace app\src\Factory;

use app\config\eloquent\DB;
use app\src\Constant\ErrorCode;
use app\src\Constant\ReferStatus;
use app\src\Repository\TahunAjaranRepository;

class TahunAjaranFactory
{
    private $tahunAjaranRepo;

    public function __construct()
    {
        $this->tahunAjaranRepo = new TahunAjaranRepository;
    }

    public function getTahunAjaran($sekolah_id, $npsn, $id = 0)
    {
        $tahun_ajar = $this->tahunAjaranRepo->getTahunAjaran($sekolah_id, $npsn, $id);

        return array(
            'status' => boolval($tahun_ajar),
            'data' => $tahun_ajar,
            'msg' => $tahun_ajar ? 'Found' : 'Not found',
            'code' => $tahun_ajar ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_BAD_REQUEST
        );
    }

    public function create($admin_id, $sekolah, $tahun_ajar)
    {
        $status = false;
        $msg = 'Admin not found';
        $date = date('Y-m-d H:i:s');

        $tahun_ajar['NPSN'] = $sekolah->NPSN;
        $tahun_ajar['sekolah_id'] = $sekolah->id;
        $tahun_ajar['year'] = $tahun_ajar['year'];
        $tahun_ajar['description'] = $tahun_ajar['description'];
        // override status. next feature
        $tahun_ajar['status'] = ReferStatus::STATUS_ACTIVE; 
        $tahun_ajar['createdate'] = $date;
        $tahun_ajar['createby'] = $admin_id;

        DB::beginTransaction();
        try {
            DB::table('tahun_ajaran')->insert($tahun_ajar);
            DB::commit();
            $status = true;
            $msg = 'Success';
        } catch (\Exception $ex) {
            DB::rollback();
            $msg = $ex->getMessage();
        }      

        return array(
            'status' => $status,
            'msg' => $msg,
            'code' => $status ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }

    public function update($admin_id, $sekolah, $tahun_ajar_id, $tahun_ajar)
    {
        $status = false;
        $msg = 'Admin not found';
        $date = date('Y-m-d H:i:s');

        $tahun_ajar['year'] = $tahun_ajar['year'];
        $tahun_ajar['description'] = $tahun_ajar['description'];
        $tahun_ajar['updatedate'] = $date;
        $tahun_ajar['updateby'] = $admin_id;

        DB::beginTransaction();
        try {
            DB::table('tahun_ajaran')
                ->where('id', '=', $tahun_ajar_id)
                ->where('NPSN', '=', $sekolah->NPSN)
                ->where('sekolah_id', '=', $sekolah->id)
                ->update($tahun_ajar);
            DB::commit();
            $status = true;
            $msg = 'Success';
        } catch (\Exception $ex) {
            DB::rollback();
            $msg = $ex->getMessage();
        }      

        return array(
            'status' => $status,
            'msg' => $msg,
            'code' => $status ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }    
}
