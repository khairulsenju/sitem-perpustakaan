<?php

namespace Arya\SistemPerpustakaan\Core;

class App {
    private $router;
    private $controller;
    private $method;
    private $params;

    public function __construct() {
        $this->router = new Router();
        $this->router->parseUrl();
        
        // Get routing information from router
        $this->controller = $this->router->getController();
        $this->method = $this->router->getMethod();
        $this->params = $this->router->getParams();
    }

    public function run() {
        try {
            // Load controller using autoloader
            $controllerClass = 'Arya\\SistemPerpustakaan\\Controllers\\' . $this->controller . 'Controller';
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass;

                // Call method if exists
                if (method_exists($controller, $this->method)) {
                    call_user_func_array([$controller, $this->method], $this->params);
                } else {
                    // Default to index method if specified method doesn't exist
                    if (method_exists($controller, 'index')) {
                        call_user_func_array([$controller, 'index'], $this->params);
                    } else {
                        // If no index method, show 404 or default to auth controller
                        $this->loadDefaultController();
                    }
                }
            } else {
                // Default to auth controller if not found
                $this->loadDefaultController();
            }
        } catch (\Exception $e) {
            // Display error for debugging
            $this->displayError($e->getMessage());
        }
    }

    private function loadDefaultController() {
        try {
            $controllerClass = 'Arya\SistemPerpustakaan\Controllers\AuthController';
            $controller = new $controllerClass;
            // Call method if exists
            if (method_exists($controller, $this->method)) {
                call_user_func_array([$controller, $this->method], $this->params);
            } else {
                // Default to showLoginForm method if specified method doesn't exist
                if (method_exists($controller, 'showLoginForm')) {
                    call_user_func_array([$controller, 'showLoginForm'], $this->params);
                }
            }
        } catch (\Exception $e) {
            // Display error for debugging
            $this->displayError($e->getMessage());
        }
    }
    
    private function displayError($message) {
        // For development, show detailed error
        if (defined('DEBUG') && DEBUG) {
            echo "<h1>Application Error</h1>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($message) . "</p>";
            echo "<p>This error occurred while trying to initialize the application.</p>";
        } else {
            // For production, show generic error
            echo "<h1>Application Error</h1>";
            echo "<p>Sorry, an error occurred while processing your request. Please try again later.</p>";
        }
    }
}