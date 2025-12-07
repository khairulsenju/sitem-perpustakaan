<?php
$title = 'Data Staff Perpustakaan - Sistem Perpustakaan';
ob_start();
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h3>👥 Data Staff Perpustakaan</h3>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID Petugas</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Nomor Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($staffList) && is_array($staffList)): ?>
                                    <?php foreach ($staffList as $staff): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($staff['Nama_Petugas'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($staff['Jabatan'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($staff['Tgl_Lahir'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($staff['No_Telp'] ?? ''); ?></td>
                                            <td>
                                                <a href="/staff/edit/<?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="/staff/delete/<?php echo htmlspecialchars($staff['ID_Petugas'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus staff ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data staff</td>
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