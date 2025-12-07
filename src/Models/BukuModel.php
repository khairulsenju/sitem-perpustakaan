<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class BukuModel extends Model {
    private $table = 'buku';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Judul";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Buku = :id";
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
            $sql = "INSERT INTO {$this->table} (ISBN, Judul, Tahun_Terbit, ID_Penerbit, ID_Kategori) VALUES (:isbn, :judul, :tahun_terbit, :id_penerbit, :id_kategori)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'isbn' => $data['ISBN'] ?? null,
                'judul' => $data['Judul'],
                'tahun_terbit' => $data['Tahun_Terbit'] ?? null,
                'id_penerbit' => $data['ID_Penerbit'] ?? null,
                'id_kategori' => $data['ID_Kategori'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET ISBN = :isbn, Judul = :judul, Tahun_Terbit = :tahun_terbit, ID_Penerbit = :id_penerbit, ID_Kategori = :id_kategori WHERE ID_Buku = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'isbn' => $data['ISBN'] ?? null,
                'judul' => $data['Judul'],
                'tahun_terbit' => $data['Tahun_Terbit'] ?? null,
                'id_penerbit' => $data['ID_Penerbit'] ?? null,
                'id_kategori' => $data['ID_Kategori'] ?? null,
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Buku = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Get books with related information (kategori, penerbit)
    public function getAllWithDetails() {
        try {
            $sql = "SELECT b.*, k.Nama_Kategori, p.Nama_Penerbit 
                    FROM {$this->table} b 
                    LEFT JOIN kategori k ON b.ID_Kategori = k.ID_Kategori 
                    LEFT JOIN penerbit p ON b.ID_Penerbit = p.ID_Penerbit 
                    ORDER BY b.Judul";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Search books by title
    public function searchByTitle($keyword) {
        try {
            $sql = "SELECT b.*, k.Nama_Kategori, p.Nama_Penerbit 
                    FROM {$this->table} b 
                    LEFT JOIN kategori k ON b.ID_Kategori = k.ID_Kategori 
                    LEFT JOIN penerbit p ON b.ID_Penerbit = p.ID_Penerbit 
                    WHERE b.Judul LIKE :keyword 
                    ORDER BY b.Judul";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['keyword' => "%{$keyword}%"]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get books with complete information including authors and inventory
    public function getAllWithCompleteDetails() {
        try {
            $sql = "SELECT 
                        b.*, 
                        k.Nama_Kategori, 
                        p.Nama_Penerbit,
                        GROUP_CONCAT(pg.Nama_Pengarang SEPARATOR ', ') as Nama_Pengarang
                    FROM {$this->table} b 
                    LEFT JOIN kategori k ON b.ID_Kategori = k.ID_Kategori 
                    LEFT JOIN penerbit p ON b.ID_Penerbit = p.ID_Penerbit 
                    LEFT JOIN penulisan pn ON b.ID_Buku = pn.ID_Buku
                    LEFT JOIN pengarang pg ON pn.ID_Pengarang = pg.ID_Pengarang
                    GROUP BY b.ID_Buku
                    ORDER BY b.Judul";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get book with complete information including authors and inventory details
    public function getWithCompleteDetails($id) {
        try {
            $sql = "SELECT 
                        b.*, 
                        k.Nama_Kategori, 
                        p.Nama_Penerbit,
                        GROUP_CONCAT(pg.Nama_Pengarang SEPARATOR ', ') as Nama_Pengarang
                    FROM {$this->table} b 
                    LEFT JOIN kategori k ON b.ID_Kategori = k.ID_Kategori 
                    LEFT JOIN penerbit p ON b.ID_Penerbit = p.ID_Penerbit 
                    LEFT JOIN penulisan pn ON b.ID_Buku = pn.ID_Buku
                    LEFT JOIN pengarang pg ON pn.ID_Pengarang = pg.ID_Pengarang
                    WHERE b.ID_Buku = :id
                    GROUP BY b.ID_Buku";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    // Search books with complete information including authors
    public function searchWithCompleteDetails($keyword) {
        try {
            $sql = "SELECT 
                        b.*, 
                        k.Nama_Kategori, 
                        p.Nama_Penerbit,
                        GROUP_CONCAT(pg.Nama_Pengarang SEPARATOR ', ') as Nama_Pengarang
                    FROM {$this->table} b 
                    LEFT JOIN kategori k ON b.ID_Kategori = k.ID_Kategori 
                    LEFT JOIN penerbit p ON b.ID_Penerbit = p.ID_Penerbit 
                    LEFT JOIN penulisan pn ON b.ID_Buku = pn.ID_Buku
                    LEFT JOIN pengarang pg ON pn.ID_Pengarang = pg.ID_Pengarang
                    WHERE b.Judul LIKE :keyword 
                    GROUP BY b.ID_Buku
                    ORDER BY b.Judul";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['keyword' => "%{$keyword}%"]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    // Get authors for a specific book
    public function getAuthorsByBookId($bookId) {
        try {
            $sql = "SELECT pg.* 
                    FROM pengarang pg
                    JOIN penulisan pn ON pg.ID_Pengarang = pn.ID_Pengarang
                    WHERE pn.ID_Buku = :id_buku
                    ORDER BY pg.Nama_Pengarang";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_buku' => $bookId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
}