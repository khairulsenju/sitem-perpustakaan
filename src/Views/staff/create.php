<?php
$title = 'Tambah Data Staff - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Data Staff</h3>
                </div>
                <div class="card-body">
                    <form action="/staff/create" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Staff</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   placeholder="Contoh: Andi Prasetyo" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" 
                                      placeholder="Contoh: Jl. Merdeka No. 123"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                   placeholder="Contoh: 081234567890">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Contoh: andi@perpustakaan.com">
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/staff" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">SIMPAN DATA STAFF</button>
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