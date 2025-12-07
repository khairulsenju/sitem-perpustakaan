<?php
$title = 'Tambah Data Eksemplar - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Tambah Data Eksemplar</h3>
                </div>
                <div class="card-body">
                    <form action="/eksemplar/create" method="POST">
                        <div class="mb-3">
                            <label for="id_buku" class="form-label">ID Buku</label>
                            <input type="text" class="form-control" id="id_buku" name="id_buku" 
                                   placeholder="Contoh: 1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_induk" class="form-label">No. Induk Inventaris</label>
                            <input type="text" class="form-control" id="no_induk" name="no_induk" 
                                   placeholder="Contoh: INV-001" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Ketersediaan</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="dipinjam">Dipinjam</option>
                                <option value="rusak">Rusak</option>
                                <option value="hilang">Hilang</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/eksemplar" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">SIMPAN DATA EKSEMPLAR</button>
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