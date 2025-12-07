<?php
$title = 'Data Eksemplar Buku - Sistem Perpustakaan';
ob_start();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>📚 Data Eksemplar Buku</h3>
            <div class="mb-3">
                <a href="/eksemplar/create" class="btn btn-primary">➕ Tambah Eksemplar</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID Eksemplar</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis</th>
                                    <th>Kategori</th>
                                    <th>Penerbit</th>
                                    <th>No. Induk Inventaris</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($eksemplarList) && is_array($eksemplarList)): ?>
                                    <?php foreach ($eksemplarList as $eksemplar): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($eksemplar['ID_Eksemplar']); ?></td>
                                            <td><?php echo htmlspecialchars($eksemplar['Judul_Buku'] ?? $eksemplar['ID_Buku']); ?></td>
                                            <td><?php echo htmlspecialchars($eksemplar['Nama_Pengarang'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($eksemplar['Nama_Kategori'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($eksemplar['Nama_Penerbit'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($eksemplar['No_Induk_Inventaris']); ?></td>
                                            <td>
                                                <?php if ($eksemplar['Status_Ketersediaan'] == 'tersedia'): ?>
                                                    <span class="badge bg-success">Tersedia</span>
                                                <?php elseif ($eksemplar['Status_Ketersediaan'] == 'dipinjam'): ?>
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($eksemplar['Status_Ketersediaan']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="/eksemplar/edit/<?php echo htmlspecialchars($eksemplar['ID_Eksemplar']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="/eksemplar/delete/<?php echo htmlspecialchars($eksemplar['ID_Eksemplar']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus eksemplar ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data eksemplar</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>