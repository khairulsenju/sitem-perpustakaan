<?php
$title = 'Tambah Data Anggota - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Data Anggota</h3>
                </div>
                <div class="card-body">
                    <form action="/anggota/create" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Anggota</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   placeholder="Contoh: Budi Santoso" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   placeholder="Contoh: 081234567890">
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_daftar" class="form-label">Tanggal Daftar</label>
                            <input type="date" class="form-control" id="tgl_daftar" name="tgl_daftar" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/anggota" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">SIMPAN DATA ANGGOTA</button>
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