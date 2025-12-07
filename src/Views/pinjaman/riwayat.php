<?php
$title = 'Riwayat Peminjaman - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <h3>📋 Daftar Seluruh Peminjaman</h3>
    
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Pinjam</th>
                            <th>ID Anggota</th>
                            <th>ID Eksemplar</th>
                            <th>ID Petugas Pinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Harus Kembali</th>
                            <th>Tanggal Aktual Kembali</th>
                            <th>Denda (Rp)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($loanHistory) && is_array($loanHistory)): ?>
                            <?php foreach ($loanHistory as $loan): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($loan['ID_Peminjaman'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['ID_Anggota'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['ID_Eksemplar'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['ID_Petugas_Pinjam'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['Tgl_Pinjam'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['Tgl_Harus_Kembali'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($loan['Tgl_Aktual_Kembali'] ?? '-'); ?></td>
                                    <td>
                                        <?php 
                                        $fineAmount = $loan['fine_amount'] ?? 0;
                                        echo 'Rp ' . number_format($fineAmount, 0, ',', '.');
                                        
                                        if ($fineAmount > 0 && !empty($loan['fine_paid'])) {
                                            echo ' <span class="badge bg-success">Lunas</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if (isset($loan['Tgl_Aktual_Kembali']) && !empty($loan['Tgl_Aktual_Kembali'])) {
                                            echo 'Sudah Dikembalikan';
                                        } else {
                                            echo 'Belum Dikembalikan';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada riwayat peminjaman</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>