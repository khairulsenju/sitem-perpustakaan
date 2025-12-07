<?php
$title = 'Pinjaman Aktif - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <h3>📦 Status Peminjaman Saya</h3>
    
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($activeLoans) && is_array($activeLoans)): ?>
                            <?php foreach ($activeLoans as $loan): ?>
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
                                        ?>
                                    </td>
                                    <td>
                                        <?php if (!isset($loan['Tgl_Aktual_Kembali']) || empty($loan['Tgl_Aktual_Kembali'])): ?>
                                            <a href="/pinjaman/kembalikan/<?php echo htmlspecialchars($loan['ID_Peminjaman'] ?? ''); ?>" class="btn btn-success btn-sm mb-1" onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?');">Kembalikan</a>
                                        <?php endif; ?>
                                        
                                        <?php if ($fineAmount > 0): ?>
                                            <?php if (!empty($loan['fine_paid'])): ?>
                                                <span class="badge bg-success">Denda Lunas</span>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#payFineModal<?php echo $loan['ID_Peminjaman']; ?>">
                                                    Bayar Denda
                                                </button>
                                                
                                                <!-- Payment Modal -->
                                                <div class="modal fade" id="payFineModal<?php echo $loan['ID_Peminjaman']; ?>" tabindex="-1" aria-labelledby="payFineModalLabel<?php echo $loan['ID_Peminjaman']; ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="payFineModalLabel<?php echo $loan['ID_Peminjaman']; ?>">Pembayaran Denda</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="/pinjaman/bayar-denda" method="POST">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="loan_id" value="<?php echo htmlspecialchars($loan['ID_Peminjaman']); ?>">
                                                                    <p><strong>ID Peminjaman:</strong> <?php echo htmlspecialchars($loan['ID_Peminjaman']); ?></p>
                                                                    <p><strong>Jumlah Denda:</strong> Rp <?php echo number_format($fineAmount, 0, ',', '.'); ?></p>
                                                                    <div class="mb-3">
                                                                        <label for="payment_method<?php echo $loan['ID_Peminjaman']; ?>" class="form-label">Metode Pembayaran</label>
                                                                        <select class="form-control" id="payment_method<?php echo $loan['ID_Peminjaman']; ?>" name="payment_method" required>
                                                                            <option value="">Pilih Metode Pembayaran</option>
                                                                            <option value="qris">QRIS</option>
                                                                            <option value="ewallet">E-Wallet</option>
                                                                            <option value="bank_transfer">Bank Transfer</option>
                                                                            <option value="cash">Cash</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" class="btn btn-primary">Bayar Denda</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada pinjaman aktif</td>
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