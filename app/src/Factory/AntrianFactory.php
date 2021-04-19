<?php

namespace app\src\Factory;

use app\src\Constant\ErrorCode;
use app\src\Repository\AntrianRepository;
use app\src\Repository\LayananRepository;

class AntrianFactory
{
    private $antrianRepository;
    private $layananRepository;

    public function __construct()
    {
        $this->antrianRepository = new AntrianRepository;
        $this->layananRepository = new LayananRepository;
    }

    public function countAntrian($id_layanan, $date = null)
    {
        $date = is_null($date) ? date('Y-m-d') : $date ;
        
        return array(
            'status' => true,
            'count' => $this->antrianRepository->countByLayananAndDate($id_layanan, $date),
            'code' => ErrorCode::CODE_SUCCESS
        );
    }

    public function getAntrian($id_layanan, $status, $showLayanan = null, $date = null)
    {
        $date = is_null($date) ? date('Y-m-d') : $date ;
        
        return array(
            'status' => true,
            'data' => $this->antrianRepository->listByLayananAndDate($id_layanan, $date, $status),
            'code' => ErrorCode::CODE_SUCCESS
        );
    }

    public function daftarAntrian($id_layanan)
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $result = array(
            'status' => false,
            'msg' => 'Empty param',
            'code' => ErrorCode::CODE_BAD_REQUEST
        );

        $nomor = $this->layananRepository->genCode($id_layanan, $date);

        if (empty($nomor)) {
            return $result;
        }

        $kode   = $nomor->kode."001";
        if($nomor){
            $nilai = substr($nomor->nomor,strlen($nomor->kode));
            $baru= intval($nilai)+1;
            
            $kode   = $nomor->kode.str_pad($baru,3,"0", STR_PAD_LEFT);
        }

        $antrian = array(
            'nomor' 		=> $kode,
            'tanggal'		=> $date,
            'waktu_datang'	=> $time,
            'id_layanan'	=> $nomor->id_layanan,
            'status'		=> '1',
            // 'kode_barcode'	=> $kode_gen
        );

        $antrian = $this->antrianRepository->saveAntrian($antrian);

        // send socket

        return array (
            'status' => boolval($antrian),
            'data' => $antrian,
            'code' => $antrian ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }
}
