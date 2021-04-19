<?php

namespace app\src\Repository;

use app\config\eloquent\DB;
use app\src\Repository\AbstractRepository;

class AdminRepository extends AbstractRepository
{  

    public function getAdmin($username, $password, $status)
    {
        $db = DB::table('administrator');
        $db->where('username', '=', $username);
        $db->where('password', '=', $password);
        $db->where('status', '=', $status);

        return $db->first();
    }

    public function findAdmin($id)
    {
        return DB::table('administrator')
            ->where('id', '=', $id)
            ->first();
    }
}
