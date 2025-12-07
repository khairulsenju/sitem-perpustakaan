<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\TransaksiPeminjamanModel;
use Arya\SistemPerpustakaan\Models\EksemplarModel;
use Arya\SistemPerpustakaan\Models\AnggotaModel;
use Arya\SistemPerpustakaan\Models\PetugasModel;
use Arya\SistemPerpustakaan\Models\BukuModel;
use Arya\SistemPerpustakaan\Models\DendaModel;

class PinjamanController extends Controller {
    public function aktif() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get active loans for the current user
        $user = $this->getSession('user');
        $pinjamanModel = new TransaksiPeminjamanModel();
        $dendaModel = new DendaModel();
        $activeLoans = $pinjamanModel->getActiveByMemberId($user['id_anggota']);
        
        // Add fine information to each loan
        foreach ($activeLoans as &$loan) {
            // Calculate fine for this loan
            $fineAmount = $pinjamanModel->calculateFine($loan);
            $loan['fine_amount'] = $fineAmount;
            
            // Check if there's already a fine record for this loan
            $existingFine = $dendaModel->getByLoanId($loan['ID_Peminjaman']);
            $loan['fine_paid'] = !empty($existingFine) && !empty($existingFine['Tgl_Pembayaran']);
            $loan['fine_id'] = $existingFine['ID_Denda'] ?? null;
        }
        
        $this->render('pinjaman/aktif', [
            'title' => 'Pinjaman Aktif - Sistem Perpustakaan',
            'activeLoans' => $activeLoans
        ]);
    }
    
    public function riwayat() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get loan history for the current user
        $user = $this->getSession('user');
        $pinjamanModel = new TransaksiPeminjamanModel();
        $dendaModel = new DendaModel();
        $loanHistory = $pinjamanModel->getByMemberId($user['id_anggota']);
        
        // Add fine information to each loan
        foreach ($loanHistory as &$loan) {
            // Calculate fine for this loan
            $fineAmount = $pinjamanModel->calculateFine($loan);
            $loan['fine_amount'] = $fineAmount;
            
            // Check if there's already a fine record for this loan
            $existingFine = $dendaModel->getByLoanId($loan['ID_Peminjaman']);
            $loan['fine_paid'] = !empty($existingFine) && !empty($existingFine['Tgl_Pembayaran']);
            $loan['fine_id'] = $existingFine['ID_Denda'] ?? null;
        }
        
        $this->render('pinjaman/riwayat', [
            'title' => 'Riwayat Pinjaman - Sistem Perpustakaan',
            'loanHistory' => $loanHistory
        ]);
    }
    
    // New method for showing the borrow form
    public function pinjam() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Handle both GET (show form) and POST (process form) requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process the borrowing request
            $user = $this->getSession('user');
            
            // Automatically use the logged-in user's ID
            $id_anggota = $user['id_anggota'];
            
            // Get book ID from form
            $bookId = $_POST['book_id'] ?? null;
            
            // Get the first available exemplar for this book
            $eksemplarModel = new EksemplarModel();
            $firstAvailableEksemplar = $eksemplarModel->getFirstAvailableByBookId($bookId);
            
            if (!$firstAvailableEksemplar) {
                $this->render('pinjaman/pinjam', [
                    'title' => 'Peminjaman Buku - Sistem Perpustakaan',
                    'error' => 'Tidak ada eksemplar yang tersedia untuk buku ini'
                ]);
                return;
            }
            
            $id_eksemplar = $firstAvailableEksemplar['ID_Eksemplar'];
            $id_petugas_pinjam = $user['id_petugas'] ?? null; // Assuming the logged-in user is the staff member
            
            // Validate required fields
            if (!$id_anggota || !$id_eksemplar || !$id_petugas_pinjam) {
                $this->render('pinjaman/pinjam', [
                    'title' => 'Peminjaman Buku - Sistem Perpustakaan',
                    'error' => 'Semua field harus diisi'
                ]);
                return;
            }
            
            // Calculate loan dates
            $tgl_pinjam = date('Y-m-d H:i:s');
            $tgl_harus_kembali = date('Y-m-d H:i:s', strtotime('+7 days')); // 7 days loan period
            
            // Create new loan record
            $pinjamanModel = new TransaksiPeminjamanModel();
            $data = [
                'ID_Anggota' => $id_anggota,
                'ID_Eksemplar' => $id_eksemplar,
                'ID_Petugas_Pinjam' => $id_petugas_pinjam,
                'Tgl_Pinjam' => $tgl_pinjam,
                'Tgl_Harus_Kembali' => $tgl_harus_kembali
            ];
            
            $result = $pinjamanModel->create($data);
            
            if ($result) {
                // Successfully created loan record
                // Update the eksemplar status to 'dipinjam'
                $eksemplarModel = new EksemplarModel();
                $eksemplarModel->update($id_eksemplar, [
                    'ID_Buku' => $firstAvailableEksemplar['ID_Buku'],
                    'No_Induk_Inventaris' => $firstAvailableEksemplar['No_Induk_Inventaris'],
                    'Status_Ketersediaan' => 'dipinjam'
                ]);
                
                // Log the book borrowing event
                $logController = new LogKunjunganController();
                $logController->logBookBorrow($id_anggota, $id_eksemplar);
                
                $this->redirect('/pinjaman/aktif');
            } else {
                // Failed to create loan record
                $this->render('pinjaman/pinjam', [
                    'title' => 'Peminjaman Buku - Sistem Perpustakaan',
                    'error' => 'Gagal membuat record peminjaman'
                ]);
            }
        } else {
            // Show the borrowing form
            $bookId = $_GET['book_id'] ?? null;
            $bookInfo = null;
            
            if ($bookId) {
                // Get book information
                $bukuModel = new BukuModel();
                $bookInfo = $bukuModel->getWithCompleteDetails($bookId);
            }
            
            $this->render('pinjaman/pinjam', [
                'title' => 'Peminjaman Buku - Sistem Perpustakaan',
                'bookId' => $bookId,
                'bookInfo' => $bookInfo
            ]);
        }
    }
    
    // Method for returning books
    public function kembalikan($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Mark book as returned
        $pinjamanModel = new TransaksiPeminjamanModel();
        $tgl_aktual_kembali = date('Y-m-d H:i:s');
        
        $result = $pinjamanModel->markAsReturned($id, $tgl_aktual_kembali);
        
        if ($result) {
            // Successfully marked as returned
            // Update the eksemplar status back to 'tersedia'
            // First, get the loan record to get the eksemplar ID
            $loan = $pinjamanModel->getById($id);
            if ($loan) {
                $eksemplarModel = new EksemplarModel();
                $eksemplar = $eksemplarModel->getById($loan['ID_Eksemplar']);
                if ($eksemplar) {
                    $eksemplarModel->update($loan['ID_Eksemplar'], [
                        'ID_Buku' => $eksemplar['ID_Buku'],
                        'No_Induk_Inventaris' => $eksemplar['No_Induk_Inventaris'],
                        'Status_Ketersediaan' => 'tersedia'
                    ]);
                }
                
                // Log the book returning event
                $logController = new LogKunjunganController();
                $logController->logBookReturn($loan['ID_Anggota'], $loan['ID_Eksemplar']);
            }
            
            $this->redirect('/pinjaman/aktif');
        } else {
            // Failed to mark as returned
            $this->redirect('/pinjaman/aktif');
        }
    }
}