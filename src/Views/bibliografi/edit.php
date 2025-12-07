<?php
$title = 'Kelola Master Bibliografi - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-white">Kelola Master Bibliografi</h3>
                </div>
                <div class="card-body">
                    <form action="/bibliografi/update/<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>">
                        
                        <div class="mb-3">
                            <label for="id_buku" class="form-label">ID_Buku</label>
                            <input type="text" class="form-control" id="id_buku" name="id_buku" 
                                   value="<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>" 
                                   placeholder="Contoh: 1" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?php echo htmlspecialchars($buku['ISBN'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="judul" name="judul" 
                                   value="<?php echo htmlspecialchars($buku['Judul'] ?? ''); ?>" 
                                   placeholder="Contoh: Algoritma dan Struktur Data" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                                   value="<?php echo htmlspecialchars($buku['Tahun_Terbit'] ?? ''); ?>" 
                                   placeholder="Contoh: 2024">
                        </div>
                        
                        <div class="mb-3">
                            <label for="penerbit_nama" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit_nama" name="penerbit_nama" 
                                   value="<?php echo htmlspecialchars($buku['Nama_Penerbit'] ?? ''); ?>" 
                                   placeholder="Masukkan nama penerbit" list="penerbit-list">
                            <datalist id="penerbit-list">
                                <?php if (isset($allPublishers) && is_array($allPublishers)): ?>
                                    <?php foreach ($allPublishers as $penerbit): ?>
                                        <option value="<?php echo htmlspecialchars($penerbit['Nama_Penerbit']); ?>">
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                            <div class="form-text">Ketik nama penerbit atau pilih dari daftar. Jika penerbit tidak ada, sistem akan membuat penerbit baru.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori_nama" class="form-label">Kategori</label>
                            <input type="text" class="form-control" id="kategori_nama" name="kategori_nama" 
                                   value="<?php echo htmlspecialchars($buku['Nama_Kategori'] ?? ''); ?>" 
                                   placeholder="Masukkan nama kategori" list="kategori-list">
                            <datalist id="kategori-list">
                                <?php if (isset($allCategories) && is_array($allCategories)): ?>
                                    <?php foreach ($allCategories as $kategori): ?>
                                        <option value="<?php echo htmlspecialchars($kategori['Nama_Kategori']); ?>">
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                            <div class="form-text">Ketik nama kategori atau pilih dari daftar. Jika kategori tidak ada, sistem akan membuat kategori baru.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="pengarang_nama" class="form-label">Pengarang</label>
                            <div id="pengarang-container">
                                <?php if (isset($currentAuthors) && is_array($currentAuthors) && !empty($currentAuthors)): ?>
                                    <?php foreach ($currentAuthors as $index => $author): ?>
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="pengarang_nama[]" 
                                                   value="<?php echo htmlspecialchars($author['Nama_Pengarang'] ?? ''); ?>" 
                                                   placeholder="Masukkan nama pengarang" list="pengarang-list">
                                            <?php if ($index > 0): ?>
                                                <button class="btn btn-outline-secondary" type="button" onclick="removeAuthorField(this)">-</button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary" type="button" onclick="addAuthorField()">+</button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="pengarang_nama[]" 
                                               placeholder="Masukkan nama pengarang" list="pengarang-list">
                                        <button class="btn btn-outline-secondary" type="button" onclick="addAuthorField()">+</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <datalist id="pengarang-list">
                                <?php if (isset($allAuthors) && is_array($allAuthors)): ?>
                                    <?php foreach ($allAuthors as $pengarang): ?>
                                        <option value="<?php echo htmlspecialchars($pengarang['Nama_Pengarang']); ?>">
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                            <div class="form-text">Ketik nama pengarang atau pilih dari daftar. Jika pengarang tidak ada, sistem akan membuat pengarang baru.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Jumlah Stok</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   value="<?php echo htmlspecialchars($buku['total_inventory'] ?? '0'); ?>" 
                                   min="0" required>
                            <div class="form-text">Stok saat ini: <?php echo htmlspecialchars($buku['total_inventory'] ?? '0'); ?></div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">UPDATE DATA BUKU</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addAuthorField() {
    const container = document.getElementById('pengarang-container');
    const newField = document.createElement('div');
    newField.className = 'input-group mb-2';
    newField.innerHTML = `
        <input type="text" class="form-control" name="pengarang_nama[]" 
               placeholder="Masukkan nama pengarang" list="pengarang-list">
        <button class="btn btn-outline-secondary" type="button" onclick="removeAuthorField(this)">-</button>
    `;
    container.appendChild(newField);
}

function removeAuthorField(button) {
    const field = button.closest('.input-group');
    field.remove();
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>