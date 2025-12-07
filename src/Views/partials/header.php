<?php
// Pastikan session sudah dimulai sebelumnya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data role dari session login
$role = isset($_SESSION['user']) ? $_SESSION['user']['role'] : 'guest'; 
$username = isset($_SESSION['user']) ? $_SESSION['user']['username'] : 'User';
$isLoggedIn = isset($_SESSION['user']);

// Debug information
error_log("Header loaded - isLoggedIn: " . ($isLoggedIn ? 'true' : 'false') . ", role: " . $role . ", username: " . $username);
?>
<header class="main-header">
    <div class="header-top">
        <div class="logo-area">
            <img src="/public/assets/logo.webp" class="logo">
        </div>

        <div class="search-area">
            <form action="/cari" method="GET" class="d-flex" style="position: relative; width: 100%;">
                <input type="text" name="q" placeholder="Cari judul buku..." class="search-input" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
            </form>
        </div>

        <div class="profile-area">
            <?php if ($isLoggedIn): ?>
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
                <a href="/profil/edit" class="profile-icon">
                    <img src="/public/assets/profile_icon.webp">
                </a>
                <a href="/logout" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="/login" class="logout-btn">Sign In</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="nav-menu">
        <!-- MENU UNTUK SEMUA (anggota, petugas, admin) -->
        <a href="/dashboard">Dashboard</a>
        <a href="/cari">Lihat Buku</a>
        <a href="/pinjaman/riwayat">Riwayat Pinjaman</a>
        <a href="/pinjaman/aktif">Pinjaman Aktif</a>

        <!-- MENU UNTUK PETUGAS DAN ADMIN -->
        <?php if ($role === 'petugas' || $role === 'admin'): ?>
            <a href="/eksemplar">Kelola Data Eksemplar</a>
            <a href="/anggota">Kelola Data Anggota</a>
        <?php endif; ?>

        <!-- MENU KHUSUS ADMIN -->
        <?php if ($role === 'admin'): ?>
            <a href="/staff">Kelola Data Staff</a>
            <a href="/bibliografi">Kelola Master Bibliografi</a>
            <a href="/kunjungan">Log Kunjungan</a>
        <?php endif; ?>
    </nav>
</header>