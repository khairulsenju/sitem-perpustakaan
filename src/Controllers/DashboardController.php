<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\BukuModel;
use Arya\SistemPerpustakaan\Models\EksemplarModel;

class DashboardController extends Controller {
    public function index() {
        // Get user from session if authenticated
        $user = $this->getSession('user');
        $isLoggedIn = $this->isAuthenticated();
        
        // Get all books data with complete details from the model
        $bukuModel = new BukuModel();
        $books = $bukuModel->getAllWithCompleteDetails();
        
        // Get inventory statistics
        $eksemplarModel = new EksemplarModel();
        $inventoryStats = $eksemplarModel->getInventoryStats();
        
        // Combine book data with inventory statistics
        // Use a new array to avoid reference issues
        $processedBooks = [];
        foreach ($books as $book) {
            $bookId = $book['ID_Buku'];
            $stats = array_filter($inventoryStats, function($stat) use ($bookId) {
                return $stat['ID_Buku'] == $bookId;
            });
            
            if (!empty($stats)) {
                $stat = reset($stats);
                $book['total_inventory'] = $stat['total'];
                $book['available_inventory'] = $stat['available'];
                $book['borrowed_inventory'] = $stat['borrowed'];
            } else {
                $book['total_inventory'] = 0;
                $book['available_inventory'] = 0;
                $book['borrowed_inventory'] = 0;
            }
            
            $processedBooks[] = $book;
        }
        
        $this->render('dashboard/index', [
            'title' => 'Dashboard - Sistem Perpustakaan',
            'user' => $user,
            'books' => $processedBooks,
            'isLoggedIn' => $isLoggedIn
        ]);
    }
}