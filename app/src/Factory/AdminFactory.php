<?php

namespace app\src\Factory;

use app\src\Constant\ErrorCode;
use app\src\Constant\ReferStatus;
use app\src\Repository\AdminRepository;

class AdminFactory
{
    private $adminRepo;

    public function __construct()
    {
        $this->adminRepo = new AdminRepository;
    }

    public function login($username, $password)
    {
        // default response
        $msg = 'Username atau password yang anda masukkan salah';

        $admin = $this->adminRepo->getAdmin(
            $username, md5($password), ReferStatus::STATUS_ACTIVE
        );
        if ($admin) {
            // unset some values & override response
            unset($admin->password);
            $msg = 'Berhasil';
        }

        return array(
            'status' => boolval($admin),
            'data' => $admin,
            'msg' => $msg,
            'code' => $admin ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_BAD_REQUEST
        );
    }
}
