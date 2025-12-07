<?php
$title = 'Register - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="text-white">Registrasi Akun</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/register" method="POST" class="register-form">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            <div class="form-text">Password minimal 6 karakter</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password" required>
                        </div>
                        
                        <!-- Hidden field to always set role as anggota -->
                        <input type="hidden" name="role" value="anggota">
                        
                        <!-- Member information (required for all users) -->
                        <div class="mb-3">
                            <label for="nama_anggota" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_anggota" name="nama_anggota" placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir">
                        </div>
                        
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Masukkan nomor telepon">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Sudah punya akun? <a href="/login">Login di sini</a></p>
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