<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\AnggotaModel;
use Arya\SistemPerpustakaan\Models\PetugasModel;

class ProfilController extends Controller {
    public function edit() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        $user = $this->getSession('user');
        
        // Get user profile data based on role
        $profileData = null;
        if ($user['role'] === 'anggota') {
            $anggotaModel = new AnggotaModel();
            $profileData = $anggotaModel->getById($user['id_anggota']);
        } else if ($user['role'] === 'petugas' || $user['role'] === 'admin') {
            $petugasModel = new PetugasModel();
            $profileData = $petugasModel->getById($user['id_petugas']);
        }
        
        $this->render('profil/edit', [
            'title' => 'Edit Profil - Sistem Perpustakaan',
            'user' => $user,
            'profileData' => $profileData
        ]);
    }
    
    public function update() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Update the user profile in the database based on role
        $user = $this->getSession('user');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($user['role'] === 'anggota') {
                $anggotaModel = new AnggotaModel();
                $data = [
                    'Nama_Anggota' => $_POST['nama'] ?? '',
                    'Tgl_Lahir' => $_POST['tgl_lahir'] ?? null,
                    'No_Telp' => $_POST['no_telp'] ?? null
                ];
                $anggotaModel->update($user['id_anggota'], $data);
            } else if ($user['role'] === 'petugas' || $user['role'] === 'admin') {
                $petugasModel = new PetugasModel();
                $data = [
                    'Nama_Petugas' => $_POST['nama'] ?? '',
                    'No_Telp' => $_POST['no_telp'] ?? null
                ];
                
                // Only update date of birth if the field exists in the Petugas table
                // For now, we'll only update name and phone number for staff
                $petugasModel->update($user['id_petugas'], $data);
            }
        }
        
        $this->redirect('/profil/edit');
    }
}