<?php

namespace app\src\Factory;

use app\src\Constant\ErrorCode;
use app\src\Object\Antrian;
use app\src\Repository\AntrianRepository;
use app\src\Repository\CsRepository;
use app\src\Repository\LayananRepository;
use app\src\Repository\LoketRepository;
use app\src\Utility\Socket;

class CsFactory
{
    private $csRepository;
    private $antrianRepository;
    private $layananRepository;
    private $loketRepository;
    private $antrian;

    public function __construct()
    {
        $this->csRepository = new CsRepository;
        $this->antrianRepository = new AntrianRepository;
        $this->layananRepository = new LayananRepository;
        $this->loketRepository = new LoketRepository;
        $this->antrian = new Antrian;
    }

    public function loginAndLog($username, $password, $isLog = true)
    {
        $ActivityType = 'Login';
        // default response
        $msg = 'Username atau password yang anda masukkan salah';

        $cs = $this->csRepository->findByUsernamePassword($username, md5($password));
        if ($cs) {
            // unset some values & override response
            unset($cs->password);
            $msg = 'Berhasil';

            $activity = $this->generateActivity($ActivityType, $cs->username, $cs->nama_cs);
            // log
            if ($isLog) $this->csRepository->logActivity('', $cs->id_cs, $activity);
        }

        return array(
            'status' => boolval($cs),
            'data' => $cs,
            'msg' => $msg,
            'code' => $cs ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_BAD_REQUEST
        );
    }

    public function callAntrianAndLog($cs, $id_layanan, $id_loket, $status, $isLog = true)
    {
        $ActivityType = 'Call';

        $date = date('Y-m-d');
        $time = date('H:i:s');

        $layanan = $this->layananRepository->getLayanan($id_layanan);
        $loket = $this->loketRepository->getLoket($id_loket);
        if (!$loket || !$layanan) {
            return array(
                'status' => false,
                'msg' => 'Loket/Layanan tidak ditemukan',
                'code' => ErrorCode::CODE_BAD_REQUEST
            );
        }

        $get = $this->antrianRepository->getOneAntrian($date, $status, $id_layanan);
        if (!$get) {
            return array(
                'status' => true,
                'msg' => 'Tidak ada antrian',
                'data' => null,
                'code' => ErrorCode::CODE_SUCCESS
            );
        }

        $activity = $this->generateActivity($ActivityType, $get->nomor, $layanan->nama_layanan, $loket->nama_loket, $cs['nama_cs']);

        $layanan = $this->layananRepository->getLayanan($id_layanan);

        $get->status = $this->antrian->status['on_call'];
        $get->id_cs = $cs['id_cs'];
        $get->id_loket = $loket->id_loket;
        $get->waktu_panggil = $time;
        $update = $this->antrianRepository->updateAntrian($get);

        if ($update) {
            // socket
            $socket = new Socket;

            // log
            if ($isLog) $this->csRepository->logActivity('', $cs['id_cs'], $activity);
        }

        return array(
            'status' => boolval($update),
            'data' => $get,
            'code' => $update ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_SERVER_FAIL
        );
    }

    public function recallAntrianAndLog($cs, $id_antrian, $isLog = true)
    {
        $ActivityType = 'Recall';

        $get = $this->antrianRepository->getAntrian($id_antrian, $this->antrian->status['on_call'], $cs['id_cs']);

        if ($get) {
            // socket
            $socket = new Socket;

            $activity = $this->generateActivity($ActivityType, $get->nomor, $get->nama_layanan, $get->nama_loket, $cs['nama_cs']);

            // log
            if ($isLog) $this->csRepository->logActivity('', $cs['id_cs'], $activity);
        }

        return array(
            'status' => boolval($get),
            'data' => $get,
            'code' => $get ? ErrorCode::CODE_SUCCESS : ErrorCode::CODE_BAD_REQUEST
        );
    }

    private function generateActivity($type, $msg1 = null, $msg2 = null, $msg3 = null, $msg4 = null)
    {
        $activity = '';
        switch ($type) {
            case 'Login':
                $activity = 'Login cs dengan username '.$msg1.' dengan nama '.$msg2;
                break;
            default :
                $activity = $type.' nomor '.$msg1.' layanan '.$msg2.' di loket '.$msg3.' oleh '.$msg4;
                break;
        }

        return $activity;
    }
}
