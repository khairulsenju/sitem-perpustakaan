<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class PenulisanModel extends Model {
    private $table = 'penulisan';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function getByBookId($bookId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Buku = :id_buku";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function getByAuthorId($authorId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Pengarang = :id_pengarang";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_pengarang' => $authorId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (ID_Buku, ID_Pengarang) VALUES (:id_buku, :id_pengarang)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_buku' => $data['ID_Buku'],
                'id_pengarang' => $data['ID_Pengarang']
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function deleteByBookId($bookId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Buku = :id_buku";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_buku' => $bookId]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function deleteByAuthorId($authorId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Pengarang = :id_pengarang";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id_pengarang' => $authorId]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Delete specific book-author relationship
    public function deleteByBookIdAndAuthorId($bookId, $authorId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Buku = :id_buku AND ID_Pengarang = :id_pengarang";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_buku' => $bookId,
                'id_pengarang' => $authorId
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}