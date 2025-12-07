<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class AnggotaModel extends Model {
    private $table = 'anggota';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Nama_Anggota";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Anggota = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function create($data) {
        try {
            error_log("AnggotaModel::create() called with data: " . print_r($data, true));
            $sql = "INSERT INTO {$this->table} (Nama_Anggota, Tgl_Lahir, No_Telp, Tanggal_Daftar) VALUES (:nama, :tgl_lahir, :no_telp, :tgl_daftar)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'nama' => $data['Nama_Anggota'],
                'tgl_lahir' => $data['Tgl_Lahir'] ?? null,
                'no_telp' => $data['No_Telp'] ?? null,
                'tgl_daftar' => $data['Tanggal_Daftar']
            ]);
            
            if ($result) {
                $id = $this->db->lastInsertId();
                error_log("AnggotaModel::create() successful, inserted ID: $id");
                return $id;
            } else {
                error_log("AnggotaModel::create() failed, execute returned false");
                return false;
            }
        } catch (\PDOException $e) {
            error_log("AnggotaModel::create() failed with exception: " . $e->getMessage());
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Nama_Anggota = :nama, Tgl_Lahir = :tgl_lahir, No_Telp = :no_telp, Tanggal_Daftar = :tgl_daftar WHERE ID_Anggota = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nama' => $data['Nama_Anggota'],
                'tgl_lahir' => $data['Tgl_Lahir'] ?? null,
                'no_telp' => $data['No_Telp'] ?? null,
                'tgl_daftar' => $data['Tanggal_Daftar'],
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Anggota = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}