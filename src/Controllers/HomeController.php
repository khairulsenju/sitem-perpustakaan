<?php

namespace Arya\SistemPerpustakaan\Controllers;

class HomeController extends AuthController {
    public function index() {
        // Show login form instead of redirecting
        $this->showLoginForm();
    }
}