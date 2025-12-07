<?php
$title = 'Kelola Data Eksemplar - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Kelola Data Eksemplar</h3>
                </div>
                <div class="card-body">
                    <form action="/eksemplar/update/<?php echo htmlspecialchars($eksemplar['ID_Eksemplar'] ?? ''); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($eksemplar['ID_Eksemplar'] ?? ''); ?>">
                        
                        <div class="mb-3">
                            <label for="id_buku" class="form-label">ID Buku</label>
                            <input type="text" class="form-control" id="id_buku" name="id_buku" 
                                   value="<?php echo htmlspecialchars($eksemplar['ID_Buku'] ?? ''); ?>" 
                                   placeholder="Contoh: 1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_induk" class="form-label">No. Induk Inventaris</label>
                            <input type="text" class="form-control" id="no_induk" name="no_induk" 
                                   value="<?php echo htmlspecialchars($eksemplar['No_Induk_Inventaris'] ?? ''); ?>" 
                                   placeholder="Contoh: INV-001" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Ketersediaan</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="tersedia" <?php echo (isset($eksemplar['Status_Ketersediaan']) && $eksemplar['Status_Ketersediaan'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                <option value="dipinjam" <?php echo (isset($eksemplar['Status_Ketersediaan']) && $eksemplar['Status_Ketersediaan'] == 'dipinjam') ? 'selected' : ''; ?>>Dipinjam</option>
                                <option value="rusak" <?php echo (isset($eksemplar['Status_Ketersediaan']) && $eksemplar['Status_Ketersediaan'] == 'rusak') ? 'selected' : ''; ?>>Rusak</option>
                                <option value="hilang" <?php echo (isset($eksemplar['Status_Ketersediaan']) && $eksemplar['Status_Ketersediaan'] == 'hilang') ? 'selected' : ''; ?>>Hilang</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">UPDATE DATA EKSEMPLAR</button>
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