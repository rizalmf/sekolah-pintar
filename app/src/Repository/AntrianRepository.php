<?php

namespace app\src\Repository;

use app\config\eloquent\DB;
use app\src\Repository\AbstractRepository;

class AntrianRepository extends AbstractRepository
{  

    public function getOneAntrian($date, $status, $id_layanan = null, $id_cs = null)
    {
        $db = DB::table('antrian');
        $db->where('tanggal', '=', $date);
        $db->where('status', '=', $status);
        $db->when($id_layanan, function ($query) use ($id_layanan) {
            return $query->where('id_layanan', '=', $id_layanan);
        });
        $db->when($id_cs, function ($query) use ($id_cs) {
            return $query->where('id_cs', '=', $id_cs);
        });
        $db->orderBy('waktu_datang');
        $db->orderBy('id_antrian');

        return $db->first();
    }

    public function getAntrian($id_antrian, $status, $id_cs = null)
    {
        return DB::table('antrian AS a')
            ->select('a.*', 'l.nama_layanan', 'lt.nama_loket')
            ->leftJoin('layanan AS l', 'l.id_layanan', '=', 'a.id_layanan')
            ->leftJoin('loket AS lt', 'lt.id_loket', '=', 'a.id_loket')
            ->where('a.id_antrian', '=', $id_antrian)
            ->where('a.status', '=', $status)
            ->when($id_cs, function ($query) use ($id_cs) {
                return $query->where('a.id_cs', '=', DB::raw($id_cs));
            })->first();
    }

    /**
     * 
     */
    public function saveAntrian($antrian)
    {
        $result = null;

        $id =  DB::table('antrian')->insertGetId($antrian);
        if ($id) {
            $db = DB::table('antrian');
            $db->where('id_antrian', '=', $id);
            $result = $db->first();
            $result->jumlah = $this->countByLayananAndDate(
                $antrian['id_layanan'], 
                $antrian['tanggal']
            ) - 1;
        }
        return $result;
    }

    public function updateAntrian($antrian)
    {
        $antrian = is_object($antrian) ? (array) $antrian : $antrian;

        return DB::table('antrian')
            ->where('id_antrian', '=', $antrian['id_antrian'])
            ->update($antrian);
    }

    /**
     * 
     */
    public function countByLayananAndDate($id_layanan, $date, $status = 1)
    {
        $db = DB::table('antrian');
        $db->where('id_layanan', '=', $id_layanan);
        $db->where('status', '=', $status);
        $db->where('tanggal', '=', $date);
        
        return $db->count();
    }

    public function listByLayananAndDate($id_layanan, $date, $status, $showLayanan = false)
    {
        $db = DB::table('antrian AS a');
        $db->when($showLayanan, function ($query) {
            return $query
                ->selectRaw('a.*, l.nama_layanan')
                ->join('layanan AS l', 'a.id_layanan', '=', 'l.id_layanan');
        });
        $db->where('a.id_layanan', '=', $id_layanan);
        $db->where('a.status', '=', $status);
        $db->where('a.tanggal', '=', $date);
        
        return $db->get()->toArray();
    }
}
