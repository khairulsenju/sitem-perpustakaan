<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\AnggotaModel;

class AnggotaController extends Controller {
    public function index() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get all anggota data from the model
        $anggotaModel = new AnggotaModel();
        $anggotaList = $anggotaModel->getAll();
        
        $this->render('anggota/index', [
            'title' => 'Kelola Data Anggota - Sistem Perpustakaan',
            'anggotaList' => $anggotaList
        ]);
    }
    
    public function edit($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Fetch the anggota data from the database
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->getById($id);
        
        $this->render('anggota/edit', [
            'title' => 'Kelola Data Anggota - Sistem Perpustakaan',
            'anggota' => $anggota
        ]);
    }
    
    public function update($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Update the anggota data in the database
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $anggotaModel = new AnggotaModel();
            $data = [
                'Nama_Anggota' => $_POST['nama'] ?? '',
                'Tgl_Lahir' => $_POST['tgl_lahir'] ?? null,
                'No_Telp' => $_POST['no_telp'] ?? null,
                'Tanggal_Daftar' => $_POST['tgl_daftar'] ?? date('Y-m-d')
            ];
            $anggotaModel->update($id, $data);
        }
        
        $this->redirect('/anggota');
    }
    
    public function delete($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Delete the anggota data from the database
        $anggotaModel = new AnggotaModel();
        $anggotaModel->delete($id);
        
        $this->redirect('/anggota');
    }
}