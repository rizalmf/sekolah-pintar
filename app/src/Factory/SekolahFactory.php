<?php

namespace app\src\Factory;

use app\config\eloquent\DB;
use app\src\Constant\ErrorCode;
use app\src\Constant\ReferStatus;
use app\src\Repository\AdminRepository;
use app\src\Repository\SekolahRepository;

class SekolahFactory
{
    private $sekolahRepo;
    private $adminRepo;

    public function __construct()
    {
        $this->sekolahRepo = new SekolahRepository;
        $this->adminRepo = new AdminRepository;
    }

    public function getActiveSekolah($admin_id, $sekolah_id = 0)
    {
        $data = $this->sekolahRepo->getSekolah(
            $admin_id, ReferStatus::STATUS_ACTIVE, $sekolah_id
        );
        return array(
            'status' => boolval($data),
            'data' => $data,
            'code' => $data ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_EMPTY
        );
    }

    public function create($admin_id, $sekolah)
    {
        $status = false;
        $msg = 'Admin not found';

        $date = date('Y-m-d H:i:s');

        $admin = $this->adminRepo->findAdmin($admin_id);

        if ($admin) {
            $sekolah['administrator_id'] = $admin_id;
            // override status. next feature
            $sekolah['status'] = ReferStatus::STATUS_ACTIVE; 
            $sekolah['createdate'] = $date;
            $sekolah['createby'] = $admin_id;

            DB::beginTransaction();
            try {
                DB::table('sekolah')->insert($sekolah);
                DB::commit();
                $status = true;
            } catch (\Exception $ex) {
                DB::rollback();
                $msg = $ex->getMessage();
            }
        }        

        return array(
            'status' => $status,
            'msg' => $msg,
            'code' => $status ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }

    public function update($admin_id, $sekolah)
    {
        $status = false;
        $msg = 'Admin not found';

        $date = date('Y-m-d H:i:s');

        $admin = $this->adminRepo->findAdmin($admin_id);

        if ($admin) {
            // override status. next feature
            $sekolah['status'] = ReferStatus::STATUS_ACTIVE; 
            $sekolah['updatedate'] = $date;
            $sekolah['updateby'] = $admin_id;

            DB::beginTransaction();
            try {
                DB::table('sekolah')
                    ->where('id', '=', $sekolah['id'])
                    ->where('administrator_id', '=', $admin_id)
                    ->update($sekolah);
                DB::commit();
                $status = true;
                $msg = 'Success';
            } catch (\Exception $ex) {
                DB::rollback();
                $msg = $ex->getMessage();
            }
        }        

        return array(
            'status' => $status,
            'msg' => $msg,
            'code' => $status ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }
}
