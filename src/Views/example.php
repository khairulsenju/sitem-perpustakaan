<?php
$title = 'Example Page - Sistem Perpustakaan';
$additional_js = ['/assets/custom.js'];
ob_start();
?>

<div class="container mt-4">
    <h1>Example Page</h1>
    <p>This is an example page using the master layout template.</p>
    
    <div class="card">
        <div class="card-header">
            <h3>Sample Card</h3>
        </div>
        <div class="card-body">
            <p>This demonstrates how content is rendered within the master layout.</p>
            <button class="btn btn-primary">Sample Button</button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/master.php';
?>