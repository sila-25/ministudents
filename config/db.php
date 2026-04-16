<?php
class Database {
    private static $instance = null;
    private $connection;
    
    // Database credentials
    private $host = 'localhost';
    private $db_name = 'ministudents_db';
    private $username = 'root';
    private $password = '';
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}

// For development without database, use JSON storage
// Uncomment this line to use JSON files instead of MySQL
// define('USE_JSON_STORAGE', true);

if (defined('USE_JSON_STORAGE') && USE_JSON_STORAGE) {
    // JSON storage helper functions
    function getJsonData($filename) {
        $file = __DIR__ . '/../data/' . $filename . '.json';
        if (!file_exists($file)) return [];
        return json_decode(file_get_contents($file), true);
    }
    
    function saveJsonData($filename, $data) {
        if (!file_exists(__DIR__ . '/../data')) {
            mkdir(__DIR__ . '/../data', 0777, true);
        }
        file_put_contents(__DIR__ . '/../data/' . $filename . '.json', json_encode($data));
    }
}
?>