<?php
session_start();
require_once 'config/config.php';
require_once 'config/db.php';
require_once 'controllers/authController.php';
require_once 'controllers/studentController.php';

// Simple routing
$page = $_GET['page'] ?? 'login';

// Check authentication for protected pages
$protected_pages = ['dashboard', 'students'];
if (in_array($page, $protected_pages) && !isset($_SESSION['user_id'])) {
    $page = 'login';
}

// Route to appropriate controller
switch($page) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new AuthController();
            $auth->login();
        } else {
            include 'views/auth/login.php';
        }
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new AuthController();
            $auth->register();
        } else {
            include 'views/auth/register.php';
        }
        break;
        
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
        
    case 'students':
    case 'dashboard':
        $studentController = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentController->addStudent();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $studentController->deleteStudent();
        } else {
            $students = $studentController->getAllStudents();
            include 'views/students.php';
        }
        break;
        
    default:
        header('Location: index.php?page=login');
        break;
}
?>