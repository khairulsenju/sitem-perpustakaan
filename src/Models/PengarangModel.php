<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class PengarangModel extends Model {
    private $table = 'pengarang';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Nama_Pengarang";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Pengarang = :id";
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
            $sql = "SELECT * FROM {$this->table} WHERE Nama_Pengarang = :name";
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
            $sql = "INSERT INTO {$this->table} (Nama_Pengarang) VALUES (:nama)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['nama' => $data['Nama_Pengarang']]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Nama_Pengarang = :nama WHERE ID_Pengarang = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nama' => $data['Nama_Pengarang'],
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Pengarang = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Check if author is referenced by any book
    public function isReferenced($authorId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM penulisan WHERE ID_Pengarang = :id_pengarang";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_pengarang' => $authorId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return ($result['count'] ?? 0) > 0;
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}