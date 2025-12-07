<?php

namespace Arya\SistemPerpustakaan\Controllers;

use Arya\SistemPerpustakaan\Core\Controller;
use Arya\SistemPerpustakaan\Models\AnggotaModel;
use Arya\SistemPerpustakaan\Models\PetugasModel;
use Arya\SistemPerpustakaan\Models\AkunLoginModel;

class AuthController extends Controller {
    public function showLoginForm() {
        // If this is a POST request, handle login instead
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->login();
            return;
        }
        
        // Get any error message from session and clear it
        $error = $this->getSession('login_error');
        $this->setSession('login_error', null);
        
        // Get any success message from session and clear it
        $success = $this->getSession('login_success');
        $this->setSession('login_success', null);
        
        $this->render('auth/login', [
            'title' => 'Login - Sistem Perpustakaan',
            'error' => $error,
            'success' => $success
        ]);
    }
    
    public function login() {
        // Handle login logic - only for POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // If not POST request, redirect to login form
            $this->redirect('/login');
            return;
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($username) || empty($password)) {
            error_log("Login attempt with missing credentials");
            $this->setSession('login_error', 'Username dan password harus diisi.');
            $this->redirect('/login');
            return;
        }
        
        error_log("Login attempt for username: " . $username);
        
        // Validate credentials against database
        $akunModel = new AkunLoginModel();
        
        // Check if database connection works
        try {
            $user = $akunModel->getByUsername($username);
            
            error_log("User found: " . print_r($user, true));
            
            if ($user && isset($user['Password_Hash']) && password_verify($password, $user['Password_Hash'])) {
                error_log("Password verified successfully for user: " . $username);
                // Clear any existing error
                $this->setSession('login_error', null);
                $this->setSession('login_success', null);
                
                // Set session data
                $this->setSession('user', [
                    'id' => $user['ID_Akun'],
                    'username' => $user['Username'],
                    'role' => $user['Role'],
                    'id_anggota' => $user['ID_Anggota'],
                    'id_petugas' => $user['ID_Petugas']
                ]);
                
                error_log("Session set for user: " . $username . " with role: " . $user['Role']);
                
                // Force session write and close
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_write_close();
                }
                
                // Redirect back to the original page if it was stored in session
                // Otherwise redirect to dashboard
                $redirectUrl = $this->getSession('redirect_after_login') ?? '/dashboard';
                $this->setSession('redirect_after_login', null);
                error_log("Redirecting to: " . $redirectUrl);
                $this->redirect($redirectUrl);
            } else {
                error_log("Login failed for username: " . $username);
                // Set error message and redirect back to login
                $this->setSession('login_error', 'Username atau password salah. Silakan coba lagi.');
                $this->redirect('/login');
            }
        } catch (\Exception $e) {
            error_log("Database error during login: " . $e->getMessage());
            $this->setSession('login_error', 'Terjadi kesalahan sistem. Silakan coba lagi nanti.');
            $this->redirect('/login');
        }
    }
    
    public function logout() {
        // Log the logout event before destroying the session
        $user = $this->getSession('user');
        if ($user) {
            // Note: This might cause issues if LogKunjunganController is not properly included
            // For now, we'll comment this out to avoid potential issues
            // $logController = new LogKunjunganController();
            // $logController->logUserLogout($user['id_anggota']);
        }
        
        $this->destroySession();
        $this->redirect('/login');
    }
    
    public function showRegisterForm() {
        // Get any error message from session and clear it
        $error = $this->getSession('register_error');
        $this->setSession('register_error', null);
        
        $this->render('auth/register', [
            'title' => 'Register - Sistem Perpustakaan',
            'error' => $error
        ]);
    }
    
    public function register() {
        // Handle registration logic
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = 'anggota'; // Always set role to anggota
            $nama_anggota = $_POST['nama_anggota'] ?? '';
            $tgl_lahir = $_POST['tgl_lahir'] ?? null;
            $no_telp = $_POST['no_telp'] ?? null;
            
            // Validate input
            if (empty($username) || empty($password) || empty($confirmPassword) || empty($nama_anggota)) {
                $this->setSession('register_error', 'Semua field wajib diisi.');
                $this->redirect('/register');
                return;
            }
            
            if ($password !== $confirmPassword) {
                $this->setSession('register_error', 'Password dan konfirmasi password tidak cocok.');
                $this->redirect('/register');
                return;
            }
            
            if (strlen($password) < 6) {
                $this->setSession('register_error', 'Password minimal 6 karakter.');
                $this->redirect('/register');
                return;
            }
            
            // Check if username already exists
            $akunModel = new AkunLoginModel();
            $existingUser = $akunModel->getByUsername($username);
            if ($existingUser) {
                $this->setSession('register_error', 'Username sudah digunakan. Silakan pilih username lain.');
                $this->redirect('/register');
                return;
            }
            
            // Create new anggota record
            $anggotaModel = new AnggotaModel();
            $anggotaData = [
                'Nama_Anggota' => $nama_anggota,
                'Tgl_Lahir' => $tgl_lahir,
                'No_Telp' => $no_telp,
                'Tanggal_Daftar' => date('Y-m-d')
            ];
            
            $id_anggota = $anggotaModel->create($anggotaData);
            if (!$id_anggota) {
                $this->setSession('register_error', 'Gagal membuat data anggota.');
                $this->redirect('/register');
                return;
            }
            
            // Create account with anggota role
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $accountData = [
                'Username' => $username,
                'Password_Hash' => $hashedPassword,
                'Role' => $role,
                'ID_Anggota' => $id_anggota,
                'ID_Petugas' => null // No petugas ID for anggota
            ];
            
            $accountId = $akunModel->createNew($accountData);
            if (!$accountId) {
                // Clean up the anggota record we just created
                $anggotaModel->delete($id_anggota);
                $this->setSession('register_error', 'Gagal membuat akun.');
                $this->redirect('/register');
                return;
            }
            
            // Registration successful, redirect to login with success message
            $this->setSession('login_success', 'Registrasi berhasil. Silakan login.');
            $this->redirect('/login');
        } else {
            // If not POST request, show registration form
            $this->showRegisterForm();
        }
    }
}