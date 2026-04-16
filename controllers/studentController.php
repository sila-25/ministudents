<?php
require_once 'models/studentModel.php';

class StudentController {
    private $studentModel;
    
    public function __construct() {
        $this->studentModel = new StudentModel();
    }
    
    public function getAllStudents() {
        return $this->studentModel->getAllByUserId($_SESSION['user_id']);
    }
    
    public function addStudent() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (empty($name) || empty($email)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'All fields are required']);
                exit;
            }
            return false;
        }
        
        $result = $this->studentModel->create($_SESSION['user_id'], $name, $email);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit;
        }
        
        return $result;
    }
    
    public function deleteStudent() {
        parse_str(file_get_contents("php://input"), $delete_vars);
        $id = $delete_vars['id'] ?? $_POST['id'] ?? null;
        
        if (!$id) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Student ID required']);
                exit;
            }
            return false;
        }
        
        $result = $this->studentModel->delete($id, $_SESSION['user_id']);
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit;
        }
        
        return $result;
    }
}
?>