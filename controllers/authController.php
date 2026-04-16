<?php
require_once 'models/userModel.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }
            
            header('Location: index.php?page=students');
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
                exit;
            }
            
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: index.php?page=login');
        }
    }
    
    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (strlen($password) < 4) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => false, 'message' => 'Password must be at least 4 characters']);
                exit;
            }
            $_SESSION['error'] = 'Password must be at least 4 characters';
            header('Location: index.php?page=register');
            return;
        }
        
        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                exit;
            }
            $_SESSION['error'] = 'Email already exists';
            header('Location: index.php?page=register');
            return;
        }
        
        if ($this->userModel->create($name, $email, $password)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => true]);
                exit;
            }
            $_SESSION['success'] = 'Registration successful! Please login.';
            header('Location: index.php?page=login');
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                echo json_encode(['success' => false, 'message' => 'Registration failed']);
                exit;
            }
            $_SESSION['error'] = 'Registration failed';
            header('Location: index.php?page=register');
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
    }
}
?>