<?php
$title = 'Kelola Data Anggota - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Kelola Data Anggota</h3>
                </div>
                <div class="card-body">
                    <form action="/anggota/update/<?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?>">
                        
                        <div class="mb-3">
                            <label for="id_anggota" class="form-label">ID_Anggota</label>
                            <input type="text" class="form-control" id="id_anggota" name="id_anggota" 
                                   value="<?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?>" 
                                   placeholder="Contoh: 1" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   value="<?php echo htmlspecialchars($anggota['Nama_Anggota'] ?? ''); ?>" 
                                   placeholder="Contoh: Arya Nugraha" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" 
                                   value="<?php echo htmlspecialchars($anggota['Tgl_Lahir'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telpon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   value="<?php echo htmlspecialchars($anggota['No_Telp'] ?? ''); ?>" 
                                   placeholder="Contoh: 08111188879">
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_daftar" class="form-label">Tanggal Daftar</label>
                            <input type="date" class="form-control" id="tgl_daftar" name="tgl_daftar" 
                                   value="<?php echo htmlspecialchars($anggota['Tanggal_Daftar'] ?? date('Y-m-d')); ?>" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">UPDATE DATA ANGGOTA</button>
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