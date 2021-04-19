<?php

namespace app\src\Repository;

use app\config\eloquent\DB;
use app\src\Repository\AbstractRepository;

class SekolahRepository extends AbstractRepository
{  

    public function getSekolah($admin_id, $status, $sekolah_id)
    {
        $db = DB::table('sekolah');
        $db->when($sekolah_id, function ($query) use ($sekolah_id) {
            return $query->where('id', '=', $sekolah_id);
        });
        $db->where('administrator_id', '=', $admin_id);
        $db->where('description', '=', '?');
        $db->where('status', '=', $status);
        $db->orderBy('id');
        
        return $db->get()->toArray();
    }
}
