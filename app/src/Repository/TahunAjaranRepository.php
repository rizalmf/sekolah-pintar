<?php

namespace app\src\Repository;

use app\config\eloquent\DB;
use app\src\Repository\AbstractRepository;

class TahunAjaranRepository extends AbstractRepository
{  

    public function getTahunAjaran($sekolah_id, $npsn, $id = 0)
    {
        $db = DB::table('tahun_ajaran');
        $db->when($id, function ($query) use ($id) {
            return $query->where('id', '=', $id);
        });
        $db->where('sekolah_id', '=', $sekolah_id);
        $db->where('NPSN', '=', $npsn);
        $db->orderBy('id');

        return $db->get()->toArray();
    }
}
