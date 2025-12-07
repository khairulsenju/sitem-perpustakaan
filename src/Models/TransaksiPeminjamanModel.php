<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class TransaksiPeminjamanModel extends Model {
    private $table = 'transaksi_peminjaman';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Tgl_Pinjam DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Peminjaman = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getByMemberId($memberId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Anggota = :id_anggota ORDER BY Tgl_Pinjam DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_anggota' => $memberId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function getActiveByMemberId($memberId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Anggota = :id_anggota AND Tgl_Aktual_Kembali IS NULL ORDER BY Tgl_Pinjam DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_anggota' => $memberId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function getAllWithDetails() {
        try {
            $sql = "SELECT tp.*, a.Nama_Anggota, e.No_Induk_Inventaris, b.Judul, p.Nama_Petugas as Nama_Petugas_Pinjam
                    FROM {$this->table} tp
                    JOIN anggota a ON tp.ID_Anggota = a.ID_Anggota
                    JOIN eksemplar e ON tp.ID_Eksemplar = e.ID_Eksemplar
                    JOIN buku b ON e.ID_Buku = b.ID_Buku
                    JOIN petugas p ON tp.ID_Petugas_Pinjam = p.ID_Petugas
                    ORDER BY tp.Tgl_Pinjam DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (ID_Anggota, ID_Eksemplar, ID_Petugas_Pinjam, Tgl_Pinjam, Tgl_Harus_Kembali) VALUES (:id_anggota, :id_eksemplar, :id_petugas_pinjam, :tgl_pinjam, :tgl_harus_kembali)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_anggota' => $data['ID_Anggota'],
                'id_eksemplar' => $data['ID_Eksemplar'],
                'id_petugas_pinjam' => $data['ID_Petugas_Pinjam'],
                'tgl_pinjam' => $data['Tgl_Pinjam'],
                'tgl_harus_kembali' => $data['Tgl_Harus_Kembali']
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET ID_Anggota = :id_anggota, ID_Eksemplar = :id_eksemplar, ID_Petugas_Pinjam = :id_petugas_pinjam, Tgl_Pinjam = :tgl_pinjam, Tgl_Harus_Kembali = :tgl_harus_kembali, Tgl_Aktual_Kembali = :tgl_aktual_kembali WHERE ID_Peminjaman = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_anggota' => $data['ID_Anggota'],
                'id_eksemplar' => $data['ID_Eksemplar'],
                'id_petugas_pinjam' => $data['ID_Petugas_Pinjam'],
                'tgl_pinjam' => $data['Tgl_Pinjam'],
                'tgl_harus_kembali' => $data['Tgl_Harus_Kembali'],
                'tgl_aktual_kembali' => $data['Tgl_Aktual_Kembali'] ?? null,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Peminjaman = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Mark book as returned
    public function markAsReturned($id, $returnDate) {
        try {
            $sql = "UPDATE {$this->table} SET Tgl_Aktual_Kembali = :tgl_aktual_kembali WHERE ID_Peminjaman = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'tgl_aktual_kembali' => $returnDate,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Calculate fine for overdue books (Rp 5,000 per day)
    public function calculateFine($loan) {
        // If the book hasn't been returned yet, check if it's overdue
        if (empty($loan['Tgl_Aktual_Kembali'])) {
            $dueDate = new \DateTime($loan['Tgl_Harus_Kembali']);
            $today = new \DateTime();
            
            // If today is after the due date, calculate the fine
            if ($today > $dueDate) {
                $interval = $dueDate->diff($today);
                $daysOverdue = $interval->days;
                return $daysOverdue * 5000; // Rp 5,000 per day
            }
        }
        // If the book has been returned, check if it was returned late
        else {
            $dueDate = new \DateTime($loan['Tgl_Harus_Kembali']);
            $returnDate = new \DateTime($loan['Tgl_Aktual_Kembali']);
            
            // If return date is after the due date, calculate the fine
            if ($returnDate > $dueDate) {
                $interval = $dueDate->diff($returnDate);
                $daysOverdue = $interval->days;
                return $daysOverdue * 5000; // Rp 5,000 per day
            }
        }
        
        // No fine if not overdue
        return 0;
    }
}