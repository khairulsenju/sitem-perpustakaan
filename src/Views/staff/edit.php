<?php
$title = 'Kelola Data Staff - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Kelola Data Staff</h3>
                </div>
                <div class="card-body">
                    <form action="/staff/update/<?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?>">
                        
                        <div class="mb-3">
                            <label for="id_petugas" class="form-label">ID_Petugas</label>
                            <input type="text" class="form-control" id="id_petugas" name="id_petugas" 
                                   value="<?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?>" 
                                   placeholder="Contoh: 1" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="<?php echo htmlspecialchars($staff['Nama_Petugas'] ?? ''); ?>" 
                                   placeholder="Contoh: Arya Nugraha" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <select class="form-control" id="jabatan" name="jabatan" required>
                                <option value="Petugas" <?php echo (isset($staff['Jabatan']) && $staff['Jabatan'] === 'Petugas') ? 'selected' : ''; ?>>Petugas</option>
                                <option value="Administrator" <?php echo (isset($staff['Jabatan']) && $staff['Jabatan'] === 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" 
                                   value="<?php echo htmlspecialchars($staff['Tgl_Lahir'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   value="<?php echo htmlspecialchars($staff['No_Telp'] ?? ''); ?>" 
                                   placeholder="Contoh: 08111188879">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">UPDATE DATA STAFF</button>
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