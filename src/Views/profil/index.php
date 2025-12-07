<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anggota - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Profil Anggota</h3>
                    </div>
                    <div class="card-body">
                        <form action="/profil/update" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($member['Email'] ?? ''); ?>" 
                                       placeholder="Contoh: arya270206@gmail.com">
                            </div>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" 
                                       value="<?php echo htmlspecialchars($member['Nama'] ?? ''); ?>" 
                                       placeholder="Contoh: Arya Nugraha">
                            </div>
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" 
                                       value="<?php echo htmlspecialchars($member['Alamat'] ?? ''); ?>" 
                                       placeholder="Contoh: Tutwuri Handayani 181">
                            </div>
                            <div class="mb-3">
                                <label for="no_telp" class="form-label">Nomor Telpon</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp" 
                                       value="<?php echo htmlspecialchars($member['No_Telp'] ?? ''); ?>" 
                                       placeholder="Contoh: 08111188879">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">SUBMIT</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>