<?php

namespace Arya\SistemPerpustakaan\Config;

class Database {
    private static $host;
    private static $databaseName;
    private static $username;
    private static $password;
    
    public static function loadEnv() {
        $envFile = dirname(__DIR__, 2) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    if ($key === 'DB_HOST') self::$host = $value;
                    if ($key === 'DB_DATABASE') self::$databaseName = $value;
                    if ($key === 'DB_USERNAME') self::$username = $value;
                    if ($key === 'DB_PASSWORD') self::$password = $value;
                }
            }
        }
    }
    
    public static function getHost() {
        if (empty(self::$host)) self::loadEnv();
        return self::$host;
    }
    
    public static function getDatabaseName() {
        if (empty(self::$databaseName)) self::loadEnv();
        return self::$databaseName;
    }
    
    public static function getUsername() {
        if (empty(self::$username)) self::loadEnv();
        return self::$username;
    }
    
    public static function getPassword() {
        if (empty(self::$password)) self::loadEnv();
        return self::$password;
    }
}
?>