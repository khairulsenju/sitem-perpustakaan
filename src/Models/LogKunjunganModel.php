<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class LogKunjunganModel extends Model {
    private $table = 'log_kunjungan';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Tgl_Waktu_Kunjungan DESC";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Kunjungan = :id";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Anggota = :id_anggota ORDER BY Tgl_Waktu_Kunjungan DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_anggota' => $memberId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (Tgl_Waktu_Kunjungan, Tujuan_Kunjungan, ID_Anggota) VALUES (:tgl_waktu, :tujuan, :id_anggota)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'tgl_waktu' => $data['Tgl_Waktu_Kunjungan'],
                'tujuan' => $data['Tujuan_Kunjungan'],
                'id_anggota' => $data['ID_Anggota'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Tgl_Waktu_Kunjungan = :tgl_waktu, Tujuan_Kunjungan = :tujuan, ID_Anggota = :id_anggota WHERE ID_Kunjungan = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'tgl_waktu' => $data['Tgl_Waktu_Kunjungan'],
                'tujuan' => $data['Tujuan_Kunjungan'],
                'id_anggota' => $data['ID_Anggota'] ?? null,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Kunjungan = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Get logs with pagination
    public function getWithPagination($limit, $offset) {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Tgl_Waktu_Kunjungan DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get total count of logs
    public function getTotalCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (\PDOException $e) {
            // Return 0 if there's an error
            return 0;
        }
    }
}