<?php

namespace app\src\Repository;

use app\config\eloquent\DB;
use app\src\Repository\AbstractRepository;

class LoketRepository extends AbstractRepository
{  
    /**
     * 
     */
    public function getLoket($id_loket = null)
    {
        $db = DB::table('loket');
        if ($id_loket) {
            $db->where('id_loket', '=', $id_loket);
            return $db->first();
        }
        $db->orderBy('id_loket');

        return $db->get()->toArray();
    }

}
