<?php
$title = isset($query) && !empty($query) ? 'Hasil Pencarian - Sistem Perpustakaan' : 'Daftar Buku - Sistem Perpustakaan';
ob_start();

// Function to highlight search terms in text
function highlightSearchTerms($text, $searchTerm) {
    if (empty($searchTerm)) {
        return htmlspecialchars($text);
    }
    
    // Escape special characters in the search term for regex
    $escapedTerm = preg_quote($searchTerm, '/');
    
    // Case-insensitive search and highlight
    return preg_replace(
        '/(' . $escapedTerm . ')/i', 
        '<mark class="bg-warning">$1</mark>', 
        htmlspecialchars($text)
    );
}
?>

<div class="container mt-4">
    <?php if (isset($query) && !empty($query)): ?>
        <h3>📑 Hasil Pencarian untuk "<?php echo htmlspecialchars($query ?? ''); ?>"</h3>
    <?php else: ?>
        <h3>📚 Daftar Buku Perpustakaan</h3>
    <?php endif; ?>
    
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>ISBN</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($searchResults) && is_array($searchResults) && count($searchResults) > 0): ?>
                            <?php foreach ($searchResults as $book): ?>
                                <tr>
                                    <td><?php echo highlightSearchTerms($book['Judul'] ?? '', $query ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Pengarang'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Kategori'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Penerbit'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Tahun_Terbit'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['ISBN'] ?? ''); ?></td>
                                    <td>
                                        <?php if (isset($book['available_inventory'])): ?>
                                            <span class="badge bg-success">Tersedia: <?php echo $book['available_inventory']; ?></span>
                                            <span class="badge bg-warning">Dipinjam: <?php echo $book['borrowed_inventory']; ?></span>
                                            <span class="badge bg-primary">Total: <?php echo $book['total_inventory']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Data tidak tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($book['available_inventory']) && $book['available_inventory'] > 0): ?>
                                            <a href="/pinjaman/pinjam?book_id=<?php echo htmlspecialchars($book['ID_Buku'] ?? ''); ?>" class="btn btn-primary btn-sm">Pinjam</a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>Tidak Tersedia</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <?php if (isset($query) && !empty($query)): ?>
                                        Tidak ada hasil pencarian untuk "<?php echo htmlspecialchars($query ?? ''); ?>"
                                    <?php else: ?>
                                        Tidak ada buku dalam perpustakaan
                                    <?php endif; ?>
                                </td>
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