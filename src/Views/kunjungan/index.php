<?php
$title = 'Log Kunjungan - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <h3>👥 Log Kunjungan</h3>
    
    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>ID Kunjungan</th>
                    <th>Waktu Kunjungan</th>
                    <th>Aktifitas</th>
                    <th>ID Anggota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($visitLogs) && is_array($visitLogs)): ?>
                    <?php foreach ($visitLogs as $index => $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['ID_Kunjungan'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($log['Tgl_Waktu_Kunjungan'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($log['Tujuan_Kunjungan'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($log['ID_Anggota'] ?? ''); ?></td>
                            <td>
                                <!-- Edit functionality removed as per requirements -->
                                <!-- Visit logs should only be used for monitoring activities and not be editable -->
                                <a href="/kunjungan/delete/<?php echo htmlspecialchars($log['ID_Kunjungan'] ?? ''); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus log kunjungan ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data log kunjungan</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>