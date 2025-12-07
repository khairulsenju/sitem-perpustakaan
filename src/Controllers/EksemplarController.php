<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\EksemplarModel;
use Arya\SistemPerpustakaan\Models\BukuModel;

class EksemplarController extends Controller {
    public function index() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get all eksemplar data with book details from the model
        $eksemplarModel = new EksemplarModel();
        $eksemplarList = $eksemplarModel->getAllWithBookDetails();
        
        // Enhance data with book information
        $bukuModel = new BukuModel();
        foreach ($eksemplarList as &$eksemplar) {
            $bookId = $eksemplar['ID_Buku'];
            if ($bookId) {
                $buku = $bukuModel->getWithCompleteDetails($bookId);
                if ($buku) {
                    $eksemplar['ISBN'] = $buku['ISBN'] ?? '';
                    $eksemplar['Tahun_Terbit'] = $buku['Tahun_Terbit'] ?? '';
                    $eksemplar['Nama_Kategori'] = $buku['Nama_Kategori'] ?? '';
                    $eksemplar['Nama_Penerbit'] = $buku['Nama_Penerbit'] ?? '';
                    $eksemplar['Nama_Pengarang'] = $buku['Nama_Pengarang'] ?? '';
                }
            }
        }
        
        $this->render('eksemplar/index', [
            'title' => 'Kelola Data Eksemplar - Sistem Perpustakaan',
            'eksemplarList' => $eksemplarList
        ]);
    }
    
    public function edit($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Fetch the eksemplar data from the database
        $eksemplarModel = new EksemplarModel();
        $eksemplar = $eksemplarModel->getById($id);
        
        $this->render('eksemplar/edit', [
            'title' => 'Kelola Data Eksemplar - Sistem Perpustakaan',
            'eksemplar' => $eksemplar
        ]);
    }
    
    public function update($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Update the eksemplar data in the database
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eksemplarModel = new EksemplarModel();
            $data = [
                'ID_Buku' => $_POST['id_buku'] ?? '',
                'No_Induk_Inventaris' => $_POST['no_induk'] ?? '',
                'Status_Ketersediaan' => $_POST['status'] ?? 'tersedia'
            ];
            $eksemplarModel->update($id, $data);
        }
        
        $this->redirect('/eksemplar');
    }
    
    public function delete($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Delete the eksemplar data from the database
        $eksemplarModel = new EksemplarModel();
        $eksemplarModel->delete($id);
        
        $this->redirect('/eksemplar');
    }
    
    public function create() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Handle both GET (show form) and POST (process form) requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create new eksemplar data in the database
            $eksemplarModel = new EksemplarModel();
            $data = [
                'ID_Buku' => $_POST['id_buku'] ?? '',
                'No_Induk_Inventaris' => $_POST['no_induk'] ?? '',
                'Status_Ketersediaan' => $_POST['status'] ?? 'tersedia'
            ];
            $eksemplarModel->create($data);
            
            $this->redirect('/eksemplar');
        } else {
            // Show the create form
            $this->render('eksemplar/create', [
                'title' => 'Tambah Data Eksemplar - Sistem Perpustakaan'
            ]);
        }
    }
}