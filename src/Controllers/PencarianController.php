<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\BukuModel;
use Arya\SistemPerpustakaan\Models\EksemplarModel;

class PencarianController extends Controller {
    public function index() {
        // Allow public access to the book listing
        // Only require authentication for actual search functionality
        
        $query = $_GET['q'] ?? '';
        
        // Search books by title using the model
        $bukuModel = new BukuModel();
        $eksemplarModel = new EksemplarModel();
        $searchResults = [];
        
        // If there's a search query, show results
        // If no query, show all books (public access)
        if (!empty($query)) {
            $searchResults = $bukuModel->searchWithCompleteDetails($query);
        } else {
            // Show all books when no search query is provided
            $searchResults = $bukuModel->getAllWithCompleteDetails();
        }
        
        // Get inventory statistics for all books
        $inventoryStats = $eksemplarModel->getInventoryStats();
        
        // Combine book data with inventory statistics
        // Use a new array to avoid reference issues
        $processedResults = [];
        foreach ($searchResults as $book) {
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
            
            $processedResults[] = $book;
        }
        
        $this->render('pencarian/index', [
            'title' => 'Daftar Buku - Sistem Perpustakaan',
            'query' => $query,
            'searchResults' => $processedResults
        ]);
    }
}