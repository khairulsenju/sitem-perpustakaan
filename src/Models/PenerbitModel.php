<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class PenerbitModel extends Model {
    private $table = 'penerbit';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Nama_Penerbit";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Penerbit = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getByName($name) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE Nama_Penerbit = :name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['name' => $name]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (Nama_Penerbit, Alamat) VALUES (:nama, :alamat)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'nama' => $data['Nama_Penerbit'],
                'alamat' => $data['Alamat'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Nama_Penerbit = :nama, Alamat = :alamat WHERE ID_Penerbit = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nama' => $data['Nama_Penerbit'],
                'alamat' => $data['Alamat'] ?? null,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Penerbit = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}