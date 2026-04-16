<?php
require_once 'config/db.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function create($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $hashed_password]);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}

// JSON storage version for development without MySQL
if (defined('USE_JSON_STORAGE') && USE_JSON_STORAGE) {
    class UserModel {
        public function findByEmail($email) {
            $users = getJsonData('users');
            foreach ($users as $user) {
                if ($user['email'] === $email) return $user;
            }
            return null;
        }
        
        public function create($name, $email, $password) {
            $users = getJsonData('users');
            $users[] = [
                'id' => uniqid(),
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ];
            saveJsonData('users', $users);
            return true;
        }
        
        public function verifyPassword($password, $hash) {
            return password_verify($password, $hash);
        }
    }
}
?>