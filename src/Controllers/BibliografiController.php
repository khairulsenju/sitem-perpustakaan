<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\BukuModel;
use Arya\SistemPerpustakaan\Models\EksemplarModel;
use Arya\SistemPerpustakaan\Models\PengarangModel;
use Arya\SistemPerpustakaan\Models\KategoriModel;
use Arya\SistemPerpustakaan\Models\PenerbitModel;
use Arya\SistemPerpustakaan\Models\PenulisanModel;

class BibliografiController extends Controller {
    public function index() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Get all books data with complete details from the model
        $bukuModel = new BukuModel();
        $bukuList = $bukuModel->getAllWithCompleteDetails();
        
        // Get inventory statistics
        $eksemplarModel = new EksemplarModel();
        $inventoryStats = $eksemplarModel->getInventoryStats();
        
        // Combine book data with inventory statistics
        foreach ($bukuList as &$buku) {
            $bookId = $buku['ID_Buku'];
            $stats = array_filter($inventoryStats, function($stat) use ($bookId) {
                return $stat['ID_Buku'] == $bookId;
            });
            
            if (!empty($stats)) {
                $stat = reset($stats);
                $buku['total_inventory'] = $stat['total'];
                $buku['available_inventory'] = $stat['available'];
                $buku['borrowed_inventory'] = $stat['borrowed'];
            } else {
                $buku['total_inventory'] = 0;
                $buku['available_inventory'] = 0;
                $buku['borrowed_inventory'] = 0;
            }
        }
        
        $this->render('bibliografi/index', [
            'title' => 'Kelola Master Bibliografi - Sistem Perpustakaan',
            'bukuList' => $bukuList
        ]);
    }
    
    public function edit($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Fetch the bibliografi data with complete details from the database
        $bukuModel = new BukuModel();
        $buku = $bukuModel->getWithCompleteDetails($id);
        
        // Get inventory statistics for this book
        $eksemplarModel = new EksemplarModel();
        $buku['total_inventory'] = $eksemplarModel->getTotalCountByBookId($id);
        $buku['available_inventory'] = $eksemplarModel->getAvailableCountByBookId($id);
        $buku['borrowed_inventory'] = $eksemplarModel->getBorrowedCountByBookId($id);
        
        // Get all authors, categories, and publishers for the dropdowns
        $pengarangModel = new PengarangModel();
        $kategoriModel = new KategoriModel();
        $penerbitModel = new PenerbitModel();
        $penulisanModel = new PenulisanModel();
        
        $allAuthors = $pengarangModel->getAll();
        $allCategories = $kategoriModel->getAll();
        $allPublishers = $penerbitModel->getAll();
        
        // Get current authors for this book
        $currentAuthors = [];
        $penulisans = $penulisanModel->getByBookId($id);
        foreach ($penulisans as $penulisan) {
            $author = $pengarangModel->getById($penulisan['ID_Pengarang']);
            if ($author) {
                $currentAuthors[] = $author;
            }
        }
        
        $this->render('bibliografi/edit', [
            'title' => 'Kelola Master Bibliografi - Sistem Perpustakaan',
            'buku' => $buku,
            'allAuthors' => $allAuthors,
            'allCategories' => $allCategories,
            'allPublishers' => $allPublishers,
            'currentAuthors' => $currentAuthors
        ]);
    }
    
    public function update($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Update the bibliografi data in the database
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bukuModel = new BukuModel();
            $pengarangModel = new PengarangModel();
            $kategoriModel = new KategoriModel();
            $penerbitModel = new PenerbitModel();
            $penulisanModel = new PenulisanModel();
            
            // Handle publisher
            $publisherId = $_POST['id_penerbit'] ?? null;
            $publisherName = $_POST['penerbit_nama'] ?? '';
            
            // If publisher name is provided and no ID, create new publisher
            if (!empty($publisherName) && empty($publisherId)) {
                $existingPublisher = $penerbitModel->getByName($publisherName);
                if ($existingPublisher) {
                    $publisherId = $existingPublisher['ID_Penerbit'];
                } else {
                    $publisherId = $penerbitModel->create(['Nama_Penerbit' => $publisherName]);
                }
            }
            
            // Handle category
            $categoryId = $_POST['id_kategori'] ?? null;
            $categoryName = $_POST['kategori_nama'] ?? '';
            
            // If category name is provided and no ID, create new category
            if (!empty($categoryName) && empty($categoryId)) {
                $existingCategory = $kategoriModel->getByName($categoryName);
                if ($existingCategory) {
                    $categoryId = $existingCategory['ID_Kategori'];
                } else {
                    $categoryId = $kategoriModel->create(['Nama_Kategori' => $categoryName]);
                }
            }
            
            $data = [
                'Judul' => $_POST['judul'] ?? '',
                'ISBN' => $_POST['isbn'] ?? null,
                'Tahun_Terbit' => $_POST['tahun_terbit'] ?? null,
                'ID_Penerbit' => $publisherId,
                'ID_Kategori' => $categoryId
            ];
            $bukuModel->update($id, $data);
            
            // Handle authors
            $authorIds = $_POST['id_pengarang'] ?? [];
            $authorNames = $_POST['pengarang_nama'] ?? [];
            
            // Get current authors for this book
            $currentPenulisans = $penulisanModel->getByBookId($id);
            $currentAuthorIds = array_column($currentPenulisans, 'ID_Pengarang');
            
            // Delete all current author associations
            $penulisanModel->deleteByBookId($id);
            
            // Add new author associations
            foreach ($authorNames as $authorName) {
                if (!empty($authorName)) {
                    // Check if author already exists
                    $existingAuthor = $pengarangModel->getByName($authorName);
                    if ($existingAuthor) {
                        $authorId = $existingAuthor['ID_Pengarang'];
                    } else {
                        // Create new author
                        $authorId = $pengarangModel->create(['Nama_Pengarang' => $authorName]);
                    }
                    
                    // Create penulisan record
                    if ($authorId) {
                        $penulisanModel->create([
                            'ID_Buku' => $id,
                            'ID_Pengarang' => $authorId
                        ]);
                    }
                }
            }
            
            // Handle stock management
            $newStockQuantity = (int)($_POST['stock_quantity'] ?? 0);
            $eksemplarModel = new EksemplarModel();
            $currentStockCount = $eksemplarModel->getTotalCountByBookId($id);
            
            if ($newStockQuantity > $currentStockCount) {
                // Add new eksemplar records
                $difference = $newStockQuantity - $currentStockCount;
                for ($i = 0; $i < $difference; $i++) {
                    $eksemplarData = [
                        'ID_Buku' => $id,
                        'No_Induk_Inventaris' => $this->generateInventoryNumber($id, $currentStockCount + $i + 1),
                        'Status_Ketersediaan' => 'tersedia'
                    ];
                    $eksemplarModel->create($eksemplarData);
                }
            } elseif ($newStockQuantity < $currentStockCount) {
                // Remove eksemplar records starting from the smallest ID
                $difference = $currentStockCount - $newStockQuantity;
                $eksemplars = $eksemplarModel->getByBookIdOrdered($id);
                
                // Delete the required number of records starting from the smallest ID
                for ($i = 0; $i < $difference && $i < count($eksemplars); $i++) {
                    $eksemplarModel->delete($eksemplars[$i]['ID_Eksemplar']);
                }
            }
            
            // Cleanup unused authors
            $this->cleanupUnusedAuthors();
        }
        
        $this->redirect('/bibliografi');
    }
    
    public function delete($id) {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Delete the bibliografi data from the database
        $bukuModel = new BukuModel();
        $bukuModel->delete($id);
        
        // Cleanup unused authors
        $this->cleanupUnusedAuthors();
        
        $this->redirect('/bibliografi');
    }
    
    public function create() {
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
            return;
        }
        
        // Handle both GET (show form) and POST (process form) requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create new bibliografi data in the database
            $bukuModel = new BukuModel();
            $pengarangModel = new PengarangModel();
            $kategoriModel = new KategoriModel();
            $penerbitModel = new PenerbitModel();
            $penulisanModel = new PenulisanModel();
            
            // Handle publisher
            $publisherId = $_POST['id_penerbit'] ?? null;
            $publisherName = $_POST['penerbit_nama'] ?? '';
            
            // If publisher name is provided and no ID, create new publisher
            if (!empty($publisherName) && empty($publisherId)) {
                $existingPublisher = $penerbitModel->getByName($publisherName);
                if ($existingPublisher) {
                    $publisherId = $existingPublisher['ID_Penerbit'];
                } else {
                    $publisherId = $penerbitModel->create(['Nama_Penerbit' => $publisherName]);
                }
            }
            
            // Handle category
            $categoryId = $_POST['id_kategori'] ?? null;
            $categoryName = $_POST['kategori_nama'] ?? '';
            
            // If category name is provided and no ID, create new category
            if (!empty($categoryName) && empty($categoryId)) {
                $existingCategory = $kategoriModel->getByName($categoryName);
                if ($existingCategory) {
                    $categoryId = $existingCategory['ID_Kategori'];
                } else {
                    $categoryId = $kategoriModel->create(['Nama_Kategori' => $categoryName]);
                }
            }
            
            $data = [
                'Judul' => $_POST['judul'] ?? '',
                'ISBN' => $_POST['isbn'] ?? null,
                'Tahun_Terbit' => $_POST['tahun_terbit'] ?? null,
                'ID_Penerbit' => $publisherId,
                'ID_Kategori' => $categoryId
            ];
            $bookId = $bukuModel->create($data);
            
            // Handle authors
            $authorNames = $_POST['pengarang_nama'] ?? [];
            
            if ($bookId) {
                foreach ($authorNames as $authorName) {
                    if (!empty($authorName)) {
                        // Check if author already exists
                        $existingAuthor = $pengarangModel->getByName($authorName);
                        if ($existingAuthor) {
                            $authorId = $existingAuthor['ID_Pengarang'];
                        } else {
                            // Create new author
                            $authorId = $pengarangModel->create(['Nama_Pengarang' => $authorName]);
                        }
                        
                        // Create penulisan record
                        if ($authorId) {
                            $penulisanModel->create([
                                'ID_Buku' => $bookId,
                                'ID_Pengarang' => $authorId
                            ]);
                        }
                    }
                }
                
                // Handle stock management - create eksemplar records
                $stockQuantity = (int)($_POST['stock_quantity'] ?? 0);
                $eksemplarModel = new EksemplarModel();
                
                for ($i = 0; $i < $stockQuantity; $i++) {
                    $eksemplarData = [
                        'ID_Buku' => $bookId,
                        'No_Induk_Inventaris' => $this->generateInventoryNumber($bookId, $i + 1),
                        'Status_Ketersediaan' => 'tersedia'
                    ];
                    $eksemplarModel->create($eksemplarData);
                }
            }
            
            $this->redirect('/bibliografi');
        } else {
            // Show the create form with all authors, categories, and publishers
            $pengarangModel = new PengarangModel();
            $kategoriModel = new KategoriModel();
            $penerbitModel = new PenerbitModel();
            
            $allAuthors = $pengarangModel->getAll();
            $allCategories = $kategoriModel->getAll();
            $allPublishers = $penerbitModel->getAll();
            
            $this->render('bibliografi/create', [
                'title' => 'Tambah Data Buku - Sistem Perpustakaan',
                'allAuthors' => $allAuthors,
                'allCategories' => $allCategories,
                'allPublishers' => $allPublishers
            ]);
        }
    }
    
    // Helper method to generate inventory numbers
    private function generateInventoryNumber($bookId, $sequence) {
        // Format: BOOKID-SEQUENCE (e.g., 123-001)
        return $bookId . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }
    
    // Cleanup unused authors
    private function cleanupUnusedAuthors() {
        $pengarangModel = new PengarangModel();
        $penulisanModel = new PenulisanModel();
        
        // Get all authors
        $authors = $pengarangModel->getAll();
        
        foreach ($authors as $author) {
            $authorId = $author['ID_Pengarang'];
            // Check if author is referenced by any book
            if (!$pengarangModel->isReferenced($authorId)) {
                // Delete unused author
                $pengarangModel->delete($authorId);
            }
        }
    }
}