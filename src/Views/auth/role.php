<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentukan Role - Sistem Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Tentukan Role</h3>
                    </div>
                    <div class="card-body">
                        <form action="/auth/role" method="POST" class="login-form">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="role" name="role" placeholder="(Anggota/Petugas/admin)" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Next</button>
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