<?php
require_once 'config/db.php';

class StudentModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
    
    public function create($user_id, $name, $email) {
        $stmt = $this->db->prepare("INSERT INTO students (user_id, name, email) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $name, $email]);
    }
    
    public function delete($id, $user_id) {
        $stmt = $this->db->prepare("DELETE FROM students WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $user_id]);
    }
}

// JSON storage version
if (defined('USE_JSON_STORAGE') && USE_JSON_STORAGE) {
    class StudentModel {
        public function getAllByUserId($user_id) {
            $students = getJsonData('students_' . $user_id);
            return $students ?: [];
        }
        
        public function create($user_id, $name, $email) {
            $students = getJsonData('students_' . $user_id);
            $students[] = [
                'id' => uniqid(),
                'name' => $name,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s')
            ];
            saveJsonData('students_' . $user_id, $students);
            return true;
        }
        
        public function delete($id, $user_id) {
            $students = getJsonData('students_' . $user_id);
            $students = array_filter($students, function($s) use ($id) {
                return $s['id'] != $id;
            });
            saveJsonData('students_' . $user_id, array_values($students));
            return true;
        }
    }
}
?>