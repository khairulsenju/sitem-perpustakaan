<?php
$title = 'Tambah Data Buku - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-white">Tambah Data Buku</h3>
                </div>
                <div class="card-body">
                    <form action="/bibliografi/create" method="POST">
                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" id="judul" name="judul" 
                                   placeholder="Contoh: Belajar PHP untuk Pemula" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   placeholder="Contoh: 978-602-1234-56-7">
                        </div>
                        
                        <div class="mb-3">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                                   placeholder="Contoh: 2023" min="1900" max="2030">
                        </div>
                        
                        <div class="mb-3">
                            <label for="penerbit_nama" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit_nama" name="penerbit_nama" 
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
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="pengarang_nama[]" 
                                           placeholder="Masukkan nama pengarang" list="pengarang-list">
                                    <button class="btn btn-outline-secondary" type="button" onclick="addAuthorField()">+</button>
                                </div>
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
                                   placeholder="Contoh: 5" min="0" required>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/bibliografi" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">SIMPAN DATA BUKU</button>
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