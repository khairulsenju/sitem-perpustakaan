<?php
$title = 'Edit Profil - Sistem Perpustakaan';
ob_start();
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3>Edit Profil</h3>
            <div class="card">
                <div class="card-body">
                    <form action="/profil/update" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="<?php echo isset($profileData['Nama_Anggota']) ? htmlspecialchars($profileData['Nama_Anggota']) : (isset($profileData['Nama_Petugas']) ? htmlspecialchars($profileData['Nama_Petugas']) : ''); ?>" 
                                   placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" 
                                   value="<?php echo isset($profileData['Tgl_Lahir']) ? htmlspecialchars($profileData['Tgl_Lahir']) : ''; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   value="<?php echo isset($profileData['No_Telp']) ? htmlspecialchars($profileData['No_Telp']) : ''; ?>" 
                                   placeholder="Masukkan nomor telepon">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>