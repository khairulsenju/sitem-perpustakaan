<?php

namespace Arya\SistemPerpustakaan\Models;

use Arya\SistemPerpustakaan\Core\Model;
use Arya\SistemPerpustakaan\Models\AnggotaModel;

class PetugasModel extends Model {
    private $table = 'petugas';
    
    public function getAll() {
        try {
            $sql = "SELECT * FROM petugas ORDER BY Nama_Petugas";
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
            $sql = "SELECT * FROM petugas WHERE ID_Petugas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getByIdWithAnggotaData($id) {
        try {
            // First get the petugas data
            $sql = "SELECT * FROM petugas WHERE ID_Petugas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $petugas = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$petugas) {
                return null;
            }
            
            // Then get the related anggota data
            $sql = "SELECT a.Tgl_Lahir, a.No_Telp FROM akun_login al 
                    JOIN anggota a ON al.ID_Anggota = a.ID_Anggota 
                    WHERE al.ID_Petugas = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $anggota = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            // Merge the data
            if ($anggota) {
                $result = array_merge($petugas, $anggota);
            } else {
                $result = $petugas;
                $result['Tgl_Lahir'] = null;
                $result['No_Telp'] = null;
            }
            
            return $result;
        } catch (\PDOException $e) {
            // Return null if there's an error
            return null;
        }
    }
    
    public function getAllWithAnggotaData() {
        try {
            // First get all petugas data
            $sql = "SELECT * FROM petugas ORDER BY Nama_Petugas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $petugasList = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Then for each petugas, get the related anggota data
            $result = [];
            foreach ($petugasList as $petugas) {
                // Get the related anggota data
                $sql = "SELECT a.Tgl_Lahir, a.No_Telp FROM akun_login al 
                        JOIN anggota a ON al.ID_Anggota = a.ID_Anggota 
                        WHERE al.ID_Petugas = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['id' => $petugas['ID_Petugas']]);
                $anggota = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                // Merge the data
                if ($anggota) {
                    $merged = array_merge($petugas, $anggota);
                } else {
                    $merged = $petugas;
                    $merged['Tgl_Lahir'] = null;
                    $merged['No_Telp'] = null;
                }
                
                $result[] = $merged;
            }
            
            return $result;
        } catch (\PDOException $e) {
            // Return empty array if there's an error
            return [];
        }
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO petugas (Nama_Petugas, Jabatan) VALUES (:nama, :jabatan)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'nama' => $data['Nama_Petugas'],
                'jabatan' => $data['Jabatan']
            ]);
            
            if ($result) {
                $id = $this->db->lastInsertId();
                return $id;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $sql = "UPDATE petugas SET Nama_Petugas = :nama, Jabatan = :jabatan WHERE ID_Petugas = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nama' => $data['Nama_Petugas'],
                'jabatan' => $data['Jabatan'],
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function updateAnggotaData($id_petugas, $anggotaData) {
        try {
            // First, get the ID_Anggota associated with this petugas through AKUN_LOGIN
            $sql = "SELECT al.ID_Anggota FROM akun_login al WHERE al.ID_Petugas = :id_petugas";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id_petugas' => $id_petugas]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$result) {
                return false;
            }
            
            $id_anggota = $result['ID_Anggota'];
            
            // Update the Anggota data
            $anggotaModel = new AnggotaModel();
            return $anggotaModel->update($id_anggota, $anggotaData);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $sql = "DELETE FROM petugas WHERE ID_Petugas = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Return false if there's an error
            return false;
        }
    }
}