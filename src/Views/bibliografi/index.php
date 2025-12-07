<?php
$title = 'Data Master Bibliografi - Sistem Perpustakaan';
ob_start();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3 class="bibliography-header">📚 Data Master Bibliografi</h3>
            <div class="mb-3">
                <a href="/bibliografi/create" class="btn btn-primary">➕ Tambah Buku</a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID Buku</th>
                                    <th>ISBN</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tahun Terbit</th>
                                    <th>Kategori</th>
                                    <th>Penerbit</th>
                                    <th>Inventaris</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($bukuList) && is_array($bukuList)): ?>
                                    <?php foreach ($bukuList as $buku): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['ISBN'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['Judul'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['Nama_Pengarang'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['Tahun_Terbit'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['Nama_Kategori'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($buku['Nama_Penerbit'] ?? ''); ?></td>
                                            <td>
                                                <span class="badge bg-success">Tersedia: <?php echo $buku['available_inventory'] ?? 0; ?></span>
                                                <span class="badge bg-warning">Dipinjam: <?php echo $buku['borrowed_inventory'] ?? 0; ?></span>
                                                <span class="badge bg-primary">Total: <?php echo $buku['total_inventory'] ?? 0; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($buku['available_inventory'] > 0): ?>
                                                    <a href="/pinjaman/pinjam?book_id=<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>" class="btn btn-primary btn-sm mb-1">Pinjam</a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm mb-1" disabled>Tidak Tersedia</button>
                                                <?php endif; ?>
                                                <br>
                                                <a href="/bibliografi/edit/<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="/bibliografi/delete/<?php echo htmlspecialchars($buku['ID_Buku'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data buku</td>
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