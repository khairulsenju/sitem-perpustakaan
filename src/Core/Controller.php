<?php

namespace Arya\SistemPerpustakaan\Core;

class Controller {
    // Method to ensure session is started only once
    protected function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            error_log("Starting session");
            session_start();
            error_log("Session started with ID: " . session_id());
        } else {
            error_log("Session already started with ID: " . session_id());
        }
    }
    
    // Method to render views
    protected function render($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Include the view file
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "View not found: " . $viewPath;
        }
    }
    
    // Method to redirect to a URL
    protected function redirect($url) {
        // Ensure the URL is properly formatted
        if (!preg_match('~^https?://~', $url)) {
            // If it's a relative URL, make sure it starts with /
            if (substr($url, 0, 1) !== '/') {
                $url = '/' . $url;
            }
        }
        
        error_log("Redirecting to: " . $url);
        header('Location: ' . $url);
        exit();
    }
    
    // Method to check if user is authenticated
    protected function isAuthenticated() {
        $this->startSession();
        error_log("isAuthenticated check - user session exists: " . (isset($_SESSION['user']) ? 'true' : 'false'));
        error_log("Session data: " . print_r($_SESSION, true));
        return isset($_SESSION['user']);
    }
    
    // Method to check if user is authenticated and redirect to login if not
    // while storing the original URL for redirect after login
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            // Store the current URL for redirect after login
            $this->setSession('redirect_after_login', $_SERVER['REQUEST_URI']);
            $this->redirect('/login');
            return false;
        }
        return true;
    }
    
    // Method to get session data
    protected function getSession($key) {
        $this->startSession();
        $value = isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        error_log("Getting session key '$key': " . ($value ? 'value set' : 'value not set'));
        return $value;
    }
    
    // Method to set session data
    protected function setSession($key, $value) {
        $this->startSession();
        $_SESSION[$key] = $value;
        error_log("Setting session key '$key' with " . (is_null($value) ? 'null' : 'value'));
    }
    
    // Method to destroy session
    protected function destroySession() {
        // Start session if not already started
        $this->startSession();
        
        error_log("Destroying session with ID: " . session_id());
        
        // Unset all session variables
        $_SESSION = array();
        
        // Delete session cookie if it exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        error_log("Session destroyed");
    }
    
    // Method to destroy session (alternative implementation)
    // protected function destroySession() {
    //     $this->startSession();
    //     session_destroy();
    //     // Also unset the session variable to ensure clean state
    //     $_SESSION = [];
    // }
}