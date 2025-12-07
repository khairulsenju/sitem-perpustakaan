<?php

namespace Arya\SistemPerpustakaan\Core;

class Router {
    private $controller = 'Dashboard';
    private $method = 'index';
    private $params = [];

    public function parseUrl() {
        // Handle root URL - show dashboard directly
        // Check multiple possible sources for the route
        $route = null;
        if (isset($_GET['route'])) {
            $route = $_GET['route'];
        } else if (isset($_SERVER['REQUEST_URI'])) {
            // Extract path from REQUEST_URI, removing query string and domain path
            $requestUri = $_SERVER['REQUEST_URI'];
            // Remove the base path if needed (adjust this based on your actual deployment path)
            // For direct deployment to htdocs, this should be empty
            $basePath = ''; // Empty for root deployment
            if (!empty($basePath) && strpos($requestUri, $basePath) === 0) {
                $requestUri = substr($requestUri, strlen($basePath));
            }
            
            // Remove query string if present
            if (strpos($requestUri, '?') !== false) {
                $requestUri = substr($requestUri, 0, strpos($requestUri, '?'));
            }
            
            $route = trim($requestUri, '/');
        }
        
        if (empty($route) || $route === '' || $route === '/') {
            $this->controller = 'Dashboard';
            $this->method = 'index';
            $this->params = [];
            return;
        }
        
        $url = rtrim($route, '/');
        // Parse URL to separate path from query parameters
        $urlParts = parse_url($url);
        $path = $urlParts['path'] ?? $url;
        $path = filter_var($path, FILTER_SANITIZE_URL);
        $url = explode('/', $path);
        
        // Ensure we don't have empty segments that could cause issues
        $url = array_filter($url, function($segment) {
            return $segment !== '';
        });
        
        // Re-index array to ensure sequential keys
        $url = array_values($url);
        
        // Filter out empty elements from the beginning of the array
        // This handles URLs that start with slashes like "/login"
        while (!empty($url) && $url[0] === '') {
            array_shift($url);
        }
        
        // If after filtering we have no elements, treat as root URL
        if (empty($url)) {
            $this->controller = 'Dashboard';
            $this->method = 'index';
            $this->params = [];
            return;
        }
        
        // Additional check to ensure we have valid URL segments
        // Filter out any remaining empty segments
        $url = array_filter($url, function($segment) {
            return $segment !== null && $segment !== '';
        });
        
        // Re-index array to ensure sequential keys
        $url = array_values($url);
        
        // If after filtering we have no elements, treat as root URL
        if (empty($url)) {
            $this->controller = 'Dashboard';
            $this->method = 'index';
            $this->params = [];
            return;
        }
        
        // Additional validation: ensure URL segments are valid
        foreach ($url as $segment) {
            // If any segment contains invalid characters, fallback to Dashboard
            if (!preg_match('/^[a-zA-Z0-9_-]*$/', $segment)) {
                $this->controller = 'Dashboard';
                $this->method = 'index';
                $this->params = [];
                return;
            }
        }
        
        // Special case: if the first segment is 'dashboard', route to DashboardController
        if (isset($url[0]) && $url[0] === 'dashboard') {
            $controllerClass = 'Arya\\SistemPerpustakaan\\Controllers\\DashboardController';
            if (class_exists($controllerClass)) {
                $this->controller = 'Dashboard';
                $this->method = 'index';
                unset($url[0]);
            } else {
                // If DashboardController doesn't exist, fallback to Dashboard
                $this->controller = 'Dashboard';
                $this->method = 'index';
            }
        } 
        // Special case: if the URL is 'login', route to AuthController::showLoginForm
        else if (isset($url[0]) && $url[0] === 'login') {
            $this->controller = 'Auth';
            $this->method = 'showLoginForm';
            unset($url[0]);
        }
        // Special case: if the URL is 'register', route to AuthController::register
        else if (isset($url[0]) && $url[0] === 'register') {
            $this->controller = 'Auth';
            $this->method = 'register';
            unset($url[0]);
        }
        // Special case: if the URL is 'logout', route to AuthController::logout
        else if (isset($url[0]) && $url[0] === 'logout') {
            $this->controller = 'Auth';
            $this->method = 'logout';
            unset($url[0]);
        }
        // Special case: if the URL is 'cari', route to PencarianController
        else if (isset($url[0]) && $url[0] === 'cari') {
            $controllerClass = 'Arya\SistemPerpustakaan\Controllers\PencarianController';
            if (class_exists($controllerClass)) {
                $this->controller = 'Pencarian';
                $this->method = 'index';
                unset($url[0]);
            } else {
                // If PencarianController doesn't exist, fallback to Dashboard
                $this->controller = 'Dashboard';
                $this->method = 'index';
            }
        }
        // Special case: if the URL is 'kunjungan', route to LogKunjunganController
        else if (isset($url[0]) && $url[0] === 'kunjungan') {
            $controllerClass = 'Arya\SistemPerpustakaan\Controllers\LogKunjunganController';
            if (class_exists($controllerClass)) {
                $this->controller = 'LogKunjungan';
                $this->method = 'index';
                unset($url[0]);
            } else {
                // If LogKunjunganController doesn't exist, fallback to Dashboard
                $this->controller = 'Dashboard';
                $this->method = 'index';
            }
        }
        else {
            // Set controller
            if (isset($url[0]) && !empty($url[0])) {
                $controllerName = ucfirst($url[0]);
                $controllerClass = 'Arya\\SistemPerpustakaan\\Controllers\\' . $controllerName . 'Controller';
                if (class_exists($controllerClass)) {
                    $this->controller = $controllerName;
                    unset($url[0]);
                } else {
                    // If controller doesn't exist, fallback to Dashboard
                    $this->controller = 'Dashboard';
                    $this->method = 'index';
                    // Clear the URL array to prevent further processing
                    $url = [];
                }
            }
            
            // Set method (this should happen regardless of whether controller was found)
            if (isset($url[1]) && !empty($url[1])) {
                $controllerClass = 'Arya\\SistemPerpustakaan\\Controllers\\' . $this->controller . 'Controller';
                if (class_exists($controllerClass) && method_exists($controllerClass, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                } else {
                    // If method doesn't exist, default to index
                    $this->method = 'index';
                }
            } else {
                // If no method specified, default to index
                $this->method = 'index';
            }
        }
        
        // Set parameters
        if (!empty($url)) {
            // Reindex array to ensure sequential keys
            $this->params = array_values($url);
        }
        
        // Safety check: ensure we always have valid controller and method
        if (empty($this->controller)) {
            $this->controller = 'Dashboard';
        }
        if (empty($this->method)) {
            $this->method = 'index';
        }
        
        // Additional safety check: if we're trying to access AuthController with showLoginForm method,
        // make sure we don't have any params that might cause issues
        if ($this->controller === 'Auth' && $this->method === 'showLoginForm') {
            $this->params = [];
        }
        
        // Final safety check: ensure we're not in an infinite loop scenario
        // If we're trying to access AuthController with login method but no POST data,
        // just show the login form directly
        if ($this->controller === 'Auth' && $this->method === 'login') {
            // We'll let the AuthController handle this properly
        }
    }
    
    public function getController() {
        return $this->controller;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    public function getParams() {
        return $this->params;
    }
}