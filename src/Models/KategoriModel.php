<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class KategoriModel extends Model {
    private $table = 'kategori';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Nama_Kategori";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Kategori = :id";
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
            $sql = "SELECT * FROM {$this->table} WHERE Nama_Kategori = :name";
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
            $sql = "INSERT INTO {$this->table} (Nama_Kategori) VALUES (:nama)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['nama' => $data['Nama_Kategori']]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Nama_Kategori = :nama WHERE ID_Kategori = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nama' => $data['Nama_Kategori'],
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Kategori = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Check if category is referenced by any book
    public function isReferenced($categoryId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM BUKU WHERE ID_Kategori = :id_kategori";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_kategori' => $categoryId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}