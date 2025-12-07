<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class DendaModel extends Model {
    private $table = 'denda';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY ID_Denda";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Denda = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getByLoanId($loanId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Peminjaman = :id_peminjaman";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_peminjaman' => $loanId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (ID_Peminjaman, Total_Denda, Tgl_Pembayaran) VALUES (:id_peminjaman, :total_denda, :tgl_pembayaran)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_peminjaman' => $data['ID_Peminjaman'],
                'total_denda' => $data['Total_Denda'],
                'tgl_pembayaran' => $data['Tgl_Pembayaran'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET ID_Peminjaman = :id_peminjaman, Total_Denda = :total_denda, Tgl_Pembayaran = :tgl_pembayaran WHERE ID_Denda = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_peminjaman' => $data['ID_Peminjaman'],
                'total_denda' => $data['Total_Denda'],
                'tgl_pembayaran' => $data['Tgl_Pembayaran'] ?? null,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Denda = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Mark fine as paid
    public function markAsPaid($id, $paymentDate) {
        try {
            $sql = "UPDATE {$this->table} SET Tgl_Pembayaran = :tgl_pembayaran WHERE ID_Denda = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'tgl_pembayaran' => $paymentDate,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}