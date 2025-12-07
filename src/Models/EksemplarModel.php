<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class EksemplarModel extends Model {
    private $table = 'eksemplar';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY ID_Eksemplar";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get all eksemplar with book details
    public function getAllWithBookDetails() {
        try {
            $sql = "SELECT e.*, b.Judul as Judul_Buku 
                    FROM {$this->table} e 
                    LEFT JOIN buku b ON e.ID_Buku = b.ID_Buku 
                    ORDER BY e.ID_Eksemplar";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Eksemplar = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
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
    
    // Get eksemplar by book ID ordered by ID (for stock management)
    public function getByBookIdOrdered($bookId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE ID_Buku = :id_buku ORDER BY ID_Eksemplar ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} (ID_Buku, No_Induk_Inventaris, Status_Ketersediaan) VALUES (:id_buku, :no_induk, :status)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'id_buku' => $data['ID_Buku'],
                'no_induk' => $data['No_Induk_Inventaris'],
                'status' => $data['Status_Ketersediaan'] ?? 'tersedia'
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET ID_Buku = :id_buku, No_Induk_Inventaris = :no_induk, Status_Ketersediaan = :status WHERE ID_Eksemplar = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_buku' => $data['ID_Buku'],
                'no_induk' => $data['No_Induk_Inventaris'],
                'status' => $data['Status_Ketersediaan'] ?? 'tersedia',
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Eksemplar = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Get available copies count for a book
    public function getAvailableCountByBookId($bookId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE ID_Buku = :id_buku AND Status_Ketersediaan = 'tersedia'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (\PDOException $e) {
            // Return 0 if there's an error
            return 0;
        }
    }
    
    // Get borrowed copies count for a book
    public function getBorrowedCountByBookId($bookId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE ID_Buku = :id_buku AND Status_Ketersediaan = 'dipinjam'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (\PDOException $e) {
            // Return 0 if there's an error
            return 0;
        }
    }
    
    // Get total copies count for a book
    public function getTotalCountByBookId($bookId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE ID_Buku = :id_buku";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
        } catch (\PDOException $e) {
            // Return 0 if there's an error
            return 0;
        }
    }
    
    // Get inventory statistics for all books
    public function getInventoryStats() {
        try {
            $sql = "SELECT 
                        ID_Buku,
                        COUNT(*) as total,
                        SUM(CASE WHEN Status_Ketersediaan = 'tersedia' THEN 1 ELSE 0 END) as available,
                        SUM(CASE WHEN Status_Ketersediaan = 'dipinjam' THEN 1 ELSE 0 END) as borrowed
                    FROM {$this->table}
                    GROUP BY ID_Buku";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get the first available exemplar for a book (ordered by ID)
    public function getFirstAvailableByBookId($bookId) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE ID_Buku = :id_buku AND Status_Ketersediaan = 'tersedia' 
                    ORDER BY ID_Eksemplar ASC 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
}