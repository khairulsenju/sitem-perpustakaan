<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\LogKunjunganModel;

class LogKunjunganController extends Controller {
    public function index() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get all visit logs
        $logKunjunganModel = new LogKunjunganModel();
        $visitLogs = $logKunjunganModel->getAll();
        
        $this->render('kunjungan/index', [
            'title' => 'Log Kunjungan - Sistem Perpustakaan',
            'visitLogs' => $visitLogs
        ]);
    }
    
    public function create() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Handle both GET (show form) and POST (process form) requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create new visit log
            $logKunjunganModel = new LogKunjunganModel();
            $data = [
                'Tgl_Waktu_Kunjungan' => $_POST['tgl_waktu'] ?? date('Y-m-d H:i:s'),
                'Tujuan_Kunjungan' => $_POST['tujuan'] ?? '',
                'ID_Anggota' => $_POST['id_anggota'] ?? null
            ];
            $logKunjunganModel->create($data);
            
            $this->redirect('/kunjungan');
        } else {
            // Show the create form
            $this->render('kunjungan/create', [
                'title' => 'Tambah Log Kunjungan - Sistem Perpustakaan'
            ]);
        }
    }
    
    // Edit functionality removed as per requirements
    // Visit logs should only be used for monitoring activities and not be editable
    public function edit($id) {
        // Redirect to index instead of allowing edit
        $this->redirect('/kunjungan');
    }
    
    // Update functionality removed as per requirements
    // Visit logs should only be used for monitoring activities and not be editable
    public function update($id) {
        // Redirect to index instead of allowing update
        $this->redirect('/kunjungan');
    }
    
    public function delete($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Delete the visit log from the database
        $logKunjunganModel = new LogKunjunganModel();
        $logKunjunganModel->delete($id);
        
        $this->redirect('/kunjungan');
    }
    
    // Method to automatically log user login events
    public function logUserLogin($userId) {
        $logKunjunganModel = new LogKunjunganModel();
        $data = [
            'Tgl_Waktu_Kunjungan' => date('Y-m-d H:i:s'),
            'Tujuan_Kunjungan' => 'Login ke sistem',
            'ID_Anggota' => $userId
        ];
        return $logKunjunganModel->create($data);
    }
    
    // Method to automatically log user logout events
    public function logUserLogout($userId) {
        $logKunjunganModel = new LogKunjunganModel();
        $data = [
            'Tgl_Waktu_Kunjungan' => date('Y-m-d H:i:s'),
            'Tujuan_Kunjungan' => 'Logout dari sistem',
            'ID_Anggota' => $userId
        ];
        return $logKunjunganModel->create($data);
    }
    
    // Method to automatically log book borrowing events
    public function logBookBorrow($userId, $bookId) {
        $logKunjunganModel = new LogKunjunganModel();
        $data = [
            'Tgl_Waktu_Kunjungan' => date('Y-m-d H:i:s'),
            'Tujuan_Kunjungan' => 'Meminjam buku dengan ID: ' . $bookId,
            'ID_Anggota' => $userId
        ];
        return $logKunjunganModel->create($data);
    }
    
    // Method to automatically log book returning events
    public function logBookReturn($userId, $bookId) {
        $logKunjunganModel = new LogKunjunganModel();
        $data = [
            'Tgl_Waktu_Kunjungan' => date('Y-m-d H:i:s'),
            'Tujuan_Kunjungan' => 'Mengembalikan buku dengan ID: ' . $bookId,
            'ID_Anggota' => $userId
        ];
        return $logKunjunganModel->create($data);
    }
}