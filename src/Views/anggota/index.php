<?php
$title = 'Data Anggota Perpustakaan - Sistem Perpustakaan';
ob_start();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>👥 Data Anggota Perpustakaan</h3>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID Anggota</th>
                                    <th>Nama</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Nomor Telpon</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($anggotaList) && is_array($anggotaList)): ?>
                                    <?php foreach ($anggotaList as $anggota): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($anggota['Nama_Anggota'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($anggota['Tgl_Lahir'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($anggota['No_Telp'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($anggota['Tanggal_Daftar'] ?? ''); ?></td>
                                            <td>
                                                <a href="/anggota/edit/<?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="/anggota/delete/<?php echo htmlspecialchars($anggota['ID_Anggota'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data anggota</td>
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