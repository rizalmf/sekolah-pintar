<?php

use app\middleware\api\admin\Auth as AdminAuth;
use app\src\Action\Api\AdminAction;
use app\src\Action\HomeAction;

// Routes

$app->get('/', HomeAction::class)
    ->setName('homepage');

// API ADMINISTRATOR
$app->group('/service', function() use ($app) {
    //login
    $app->post('/login', AdminAction::class.':login');

    // API ADMINISTRATOR group with Auth
    $app->group('', function() use ($app) {
        // CRUD sekolah
        $app->get('/sekolah', AdminAction::class.':sekolah');
        $app->get('/sekolah/{id}', AdminAction::class.':sekolah');
        $app->post('/sekolah', AdminAction::class.':createSekolah');
        $app->put('/sekolah/{id}', AdminAction::class.':updateSekolah');

        // CRUD tahun_ajaran
        $app->get('/tahun_ajaran/{sekolah_id}', AdminAction::class.':tahunAjaran');
        $app->get('/tahun_ajaran/{sekolah_id}/{id}', AdminAction::class.':tahunAjaran');
        $app->post('/tahun_ajaran/{sekolah_id}', AdminAction::class.':createTahunAjaran');
        $app->put('/tahun_ajaran/{sekolah_id}/{id}', AdminAction::class.':updateTahunAjaran');
        // temporary disable
        // $app->delete('/tahun_ajaran/{sekolah_id}/{id}', AdminAction::class.':deleteTahunAjaran');

        // CRUD guru
        $app->get('/guru/{sekolah_id}', AdminAction::class.':guru');
        $app->get('/guru/{sekolah_id}/{id}', AdminAction::class.':guru');
        $app->post('/guru/{sekolah_id}', AdminAction::class.':createGuru');
        $app->put('/guru/{sekolah_id}/{id}', AdminAction::class.':updateGuru');
        $app->delete('/guru/{sekolah_id}/{id}', AdminAction::class.':deleteGuru');

        // CRUD siswa
        $app->get('/siswa/{sekolah_id}', AdminAction::class.':siswa');
        $app->get('/siswa/{sekolah_id}/{id}', AdminAction::class.':siswa');
        $app->post('/siswa/{sekolah_id}', AdminAction::class.':createSiswa');
        $app->put('/siswa/{sekolah_id}/{id}', AdminAction::class.':updateSiswa');
        $app->delete('/siswa/{sekolah_id}/{id}', AdminAction::class.':deleteSiswa');

        // CRUD kelas
        $app->get('/kelas/{sekolah_id}', AdminAction::class.':kelas');
        $app->get('/kelas/{sekolah_id}/{id}', AdminAction::class.':kelas');
        $app->post('/kelas/{sekolah_id}', AdminAction::class.':createKelas');
        $app->put('/kelas/{sekolah_id}/{id}', AdminAction::class.':updateKelas');
        $app->delete('/kelas/{sekolah_id}/{id}', AdminAction::class.':deleteKelas');

        // insert
        $app->post('/kelas/{sekolah_id}/{mapel_id}/{id}', AdminAction::class.':createDataMapel');

        // CRUD mapel
        $app->get('/mapel/{sekolah_id}', AdminAction::class.':mapel');
        $app->get('/mapel/{sekolah_id}/{id}', AdminAction::class.':mapel');
        $app->post('/mapel/{sekolah_id}', AdminAction::class.':createMapel');
        $app->put('/mapel/{sekolah_id}/{id}', AdminAction::class.':updateMapel');
        $app->delete('/mapel/{sekolah_id}/{id}', AdminAction::class.':deleteMapel');

    })->add(new AdminAuth);

});

// API GURU
$app->group('/guru', function() use ($app) {

});

// API SISWA
$app->group('/siswa', function() use ($app) {

});