<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;

class AkunLoginModel extends Model {
    private $table = 'akun_login';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM {$this->table} ORDER BY Username";
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
            $sql = "SELECT * FROM {$this->table} WHERE ID_Akun = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE Username = :username";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['username' => $username]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Database error in getByUsername: " . $e->getMessage());
            // Return null if there's an error
            return null;
        } catch (\Exception $e) {
            error_log("General error in getByUsername: " . $e->getMessage());
            // Return null if there's an error
            return null;
        }
    }
    
    public function create($data) {
        try {
            error_log("AkunLoginModel::create() called with data: " . print_r($data, true));
            // Fixed the column name from ID_Terkait to ID_Anggota
            $sql = "INSERT INTO {$this->table} (Username, Password_Hash, Role, ID_Anggota, ID_Petugas) VALUES (:username, :password_hash, :role, :id_anggota, :id_petugas)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'username' => $data['Username'],
                'password_hash' => $data['Password_Hash'],
                'role' => $data['Role'],
                'id_anggota' => $data['ID_Anggota'], // Fixed column name
                'id_petugas' => $data['ID_Petugas']  // Fixed column name
            ]);
            
            if ($result) {
                $id = $this->db->lastInsertId();
                error_log("AkunLoginModel::create() successful, inserted ID: $id");
                return $id;
            } else {
                error_log("AkunLoginModel::create() failed, execute returned false");
                return false;
            }
        } catch (\PDOException $e) {
            error_log("AkunLoginModel::create() failed with exception: " . $e->getMessage());
            // Return false if there's an error
            return false;
        }
    }
    
    public function createNew($data) {
        try {
            error_log("AkunLoginModel::createNew() called with data: " . print_r($data, true));
            // For anggota roles, ID_Petugas should be NULL, for petugas/admin roles, both ID_Anggota and ID_Petugas should have values
            $sql = "INSERT INTO {$this->table} (Username, Password_Hash, Role, ID_Anggota, ID_Petugas) VALUES (:username, :password_hash, :role, :id_anggota, :id_petugas)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'username' => $data['Username'],
                'password_hash' => $data['Password_Hash'],
                'role' => $data['Role'],
                'id_anggota' => $data['ID_Anggota'],
                'id_petugas' => $data['ID_Petugas'] // This can be NULL for anggota role
            ]);
            
            if ($result) {
                $id = $this->db->lastInsertId();
                error_log("AkunLoginModel::createNew() successful, inserted ID: $id");
                return $id;
            } else {
                error_log("AkunLoginModel::createNew() failed, execute returned false");
                return false;
            }
        } catch (\PDOException $e) {
            error_log("AkunLoginModel::createNew() failed with exception: " . $e->getMessage());
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE {$this->table} SET Username = :username, Password_Hash = :password_hash, Role = :role, ID_Anggota = :id_anggota, ID_Petugas = :id_petugas WHERE ID_Akun = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'username' => $data['Username'],
                'password_hash' => $data['Password_Hash'],
                'role' => $data['Role'],
                'id_anggota' => $data['ID_Anggota'],
                'id_petugas' => $data['ID_Petugas'],
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Akun = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    // Delete account by related ID (for anggota or petugas)
    public function deleteByRelatedId($relatedId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE ID_Anggota = :id_anggota OR ID_Petugas = :id_petugas";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'id_anggota' => $relatedId,
                'id_petugas' => $relatedId
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}