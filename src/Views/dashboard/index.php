<?php
$title = 'Dashboard - Sistem Perpustakaan';
ob_start();
?>

<div class="container mt-4">
    <!-- Welcome Card with Sliding Text and Animated Background -->
    <div class="welcome-card">
        <div class="welcome-background" id="welcomeBackground" style="background-image: url('/public/assets/open-book-on-blue-background.webp');"></div>
        <div class="welcome-content">
            <div class="welcome-text-container">
                <div class="welcome-text-slider" id="welcomeSlider">
                    <div class="welcome-text-slide">
                        <h2>Selamat Datang di Sistem Perpustakaan Digital</h2>
                        <p>Temukan ribuan buku dan materi pembelajaran dalam genggaman Anda</p>
                    </div>
                    <div class="welcome-text-slide">
                        <h2>Akses Mudah dan Cepat</h2>
                        <p>Pinjam buku kapan saja dan di mana saja dengan beberapa klik saja</p>
                    </div>
                    <div class="welcome-text-slide">
                        <h2>Koleksi Lengkap</h2>
                        <p>Berbagai genre dan kategori buku dari fiksi hingga non-fiksi</p>
                    </div>
                    <div class="welcome-text-slide">
                        <h2>Sistem Terintegrasi</h2>
                        <p>Kelola peminjaman, pengembalian, dan riwayat dengan mudah</p>
                    </div>
                </div>
            </div>
            <div class="welcome-navigation" id="welcomeDots">
                <div class="welcome-dot active" data-index="0"></div>
                <div class="welcome-dot" data-index="1"></div>
                <div class="welcome-dot" data-index="2"></div>
                <div class="welcome-dot" data-index="3"></div>
            </div>
            <button class="welcome-nav-btn prev" id="prevBtn">❮</button>
            <button class="welcome-nav-btn next" id="nextBtn">❯</button>
        </div>
    </div>
    
    <?php if (!isset($isLoggedIn) || !$isLoggedIn): ?>
        <div class="alert alert-info mt-3">
            <h4>Selamat Datang di Sistem Perpustakaan!</h4>
            <p>Untuk dapat meminjam buku dan mengakses fitur lengkap, silakan <a href="/login">masuk</a> atau <a href="/register">daftar</a> terlebih dahulu.</p>
        </div>
    <?php endif; ?>
    
    <h3>📚 Daftar Buku Master</h3>
    
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>ID Buku</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>Kategori</th>
                            <th>Inventaris</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($books) && is_array($books)): ?>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($book['ID_Buku'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Judul'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Pengarang'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Penerbit'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Tahun_Terbit'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($book['Nama_Kategori'] ?? ''); ?></td>
                                    <td>
                                        <span class="badge bg-success">Tersedia: <?php echo $book['available_inventory'] ?? 0; ?></span>
                                        <span class="badge bg-warning">Dipinjam: <?php echo $book['borrowed_inventory'] ?? 0; ?></span>
                                        <span class="badge bg-primary">Total: <?php echo $book['total_inventory'] ?? 0; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data buku</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Welcome card slider functionality
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('welcomeSlider');
    const dots = document.querySelectorAll('.welcome-dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const backgrounds = [
        '/public/assets/open-book-on-blue-background.webp',
        '/public/assets/bookselfBlue-Background.webp',
        '/public/assets/bookself-background.webp',
        '/public/assets/open-book-on-blue-background.webp'
    ];
    const backgroundElement = document.getElementById('welcomeBackground');
    
    let currentIndex = 0;
    const slideCount = 4;
    const slideHeight = 150; // Height of each slide in pixels
    
    // Function to update slider position
    function updateSlider() {
        slider.style.transform = `translateY(${-currentIndex * slideHeight}px)`;
        
        // Update active dot
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        
        // Change background with fade effect
        backgroundElement.classList.remove('fade-in');
        setTimeout(() => {
            backgroundElement.style.backgroundImage = `url('${backgrounds[currentIndex]}')`;
            backgroundElement.classList.add('fade-in');
        }, 500);
    }
    
    // Next slide
    function nextSlide() {
        currentIndex = (currentIndex + 1) % slideCount;
        updateSlider();
    }
    
    // Previous slide
    function prevSlide() {
        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
        updateSlider();
    }
    
    // Auto slide every 5 seconds
    let autoSlide = setInterval(nextSlide, 5000);
    
    // Event listeners for buttons
    nextBtn.addEventListener('click', function() {
        clearInterval(autoSlide);
        nextSlide();
        autoSlide = setInterval(nextSlide, 5000);
    });
    
    prevBtn.addEventListener('click', function() {
        clearInterval(autoSlide);
        prevSlide();
        autoSlide = setInterval(nextSlide, 5000);
    });
    
    // Event listeners for dots
    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            clearInterval(autoSlide);
            currentIndex = parseInt(this.getAttribute('data-index'));
            updateSlider();
            autoSlide = setInterval(nextSlide, 5000);
        });
    });
    
    // Pause auto slide when hovering over the card
    const welcomeCard = document.querySelector('.welcome-card');
    welcomeCard.addEventListener('mouseenter', function() {
        clearInterval(autoSlide);
    });
    
    welcomeCard.addEventListener('mouseleave', function() {
        autoSlide = setInterval(nextSlide, 5000);
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/master.php';
?>