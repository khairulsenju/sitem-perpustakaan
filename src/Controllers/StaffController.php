<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\PetugasModel;
use Arya\SistemPerpustakaan\Models\AnggotaModel;

class StaffController extends Controller {
    public function index() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get all staff data from the model with Anggota data
        $petugasModel = new PetugasModel();
        $staffList = $petugasModel->getAllWithAnggotaData();
        
        $this->render('staff/index', [
            'title' => 'Kelola Data Staff - Sistem Perpustakaan',
            'staffList' => $staffList
        ]);
    }
    
    public function edit($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Fetch the staff data from the database with Anggota data
        $petugasModel = new PetugasModel();
        $staff = $petugasModel->getByIdWithAnggotaData($id);
        
        $this->render('staff/edit', [
            'title' => 'Kelola Data Staff - Sistem Perpustakaan',
            'staff' => $staff
        ]);
    }
    
    public function update($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Update the staff data in the database
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $petugasModel = new PetugasModel();
            
            // Update Petugas data
            $petugasData = [
                'Nama_Petugas' => $_POST['nama'] ?? '',
                'Jabatan' => $_POST['jabatan'] ?? ''
            ];
            $petugasModel->update($id, $petugasData);
            
            // Update Anggota data (phone number and date of birth)
            $anggotaData = [
                'Tgl_Lahir' => $_POST['tgl_lahir'] ?? null,
                'No_Telp' => $_POST['no_telp'] ?? null
            ];
            $petugasModel->updateAnggotaData($id, $anggotaData);
        }
        
        $this->redirect('/staff');
    }
    
    public function delete($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Delete the staff data from the database
        $petugasModel = new PetugasModel();
        $petugasModel->delete($id);
        
        $this->redirect('/staff');
    }
}