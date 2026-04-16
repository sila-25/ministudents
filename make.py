import os

# ✅ Use current directory (IMPORTANT FIX)
BASE_DIR = "."

folders = [
    "controllers",
    "models",
    "views/auth",
    "assets",
    "config"
]

files = {
"index.php": """<?php
require_once "config/config.php";
require_once CONTROLLER_PATH . "studentController.php";
require_once CONTROLLER_PATH . "authController.php";

$page = $_GET['page'] ?? 'login';

$studentController = new StudentController();
$authController = new AuthController();

switch ($page) {

    case 'students':
        $studentController->students();
        break;

    case 'add':
        $studentController->store();
        break;

    case 'login':
        $authController->login();
        break;

    case 'register':
        $authController->register();
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        $authController->login();
}
""",

"config/config.php": """<?php
define('BASE_URL', 'http://localhost/ministudents/');
define('CONTROLLER_PATH', 'controllers/');
define('MODEL_PATH', 'models/');
define('VIEW_PATH', 'views/');
define('CONFIG_PATH', 'config/');

session_start();
""",

"config/db.php": """<?php
class Database {
    public function connect() {
        return new PDO("mysql:host=localhost;dbname=ministudent", "root", "");
    }
}
""",

"models/studentModel.php": """<?php
require_once CONFIG_PATH . "db.php";

class StudentModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function getStudents() {
        return $this->conn->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStudent($name) {
        $stmt = $this->conn->prepare("INSERT INTO students(name) VALUES(?)");
        $stmt->execute([$name]);
    }
}
""",

"models/userModel.php": """<?php
require_once CONFIG_PATH . "db.php";

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
        $stmt->execute([$username, md5($password)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($username, $password) {
        $stmt = $this->conn->prepare("INSERT INTO users(username,password) VALUES(?,?)");
        $stmt->execute([$username, md5($password)]);
    }
}
""",

"controllers/studentController.php": """<?php
require_once MODEL_PATH . "studentModel.php";

class StudentController {

    public function students() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?page=login");
            exit;
        }

        $students = (new StudentModel())->getStudents();
        include VIEW_PATH . "students.php";
    }

    public function store() {
        if (!isset($_SESSION['user'])) exit;

        (new StudentModel())->addStudent($_POST['name']);
        echo json_encode(["status"=>"ok"]);
    }
}
""",

"controllers/authController.php": """<?php
require_once MODEL_PATH . "userModel.php";

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = (new UserModel())->login($_POST['username'], $_POST['password']);

            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: ?page=students");
            }
        }

        include VIEW_PATH . "auth/login.php";
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            (new UserModel())->register($_POST['username'], $_POST['password']);
            header("Location: ?page=login");
        }

        include VIEW_PATH . "auth/register.php";
    }

    public function logout() {
        session_destroy();
        header("Location: ?page=login");
    }
}
""",

"views/auth/login.php": """<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="center">

<div class="card glass">
<h2>Login</h2>

<form method="POST">
<input name="username" placeholder="Username" required>
<input name="password" type="password" placeholder="Password" required>
<button>Login</button>
</form>

<a href="?page=register">Create account</a>
</div>

</body>
</html>
""",

"views/auth/register.php": """<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="center">

<div class="card glass">
<h2>Register</h2>

<form method="POST">
<input name="username" required>
<input name="password" type="password" required>
<button>Register</button>
</form>

</div>

</body>
</html>
""",

"views/students.php": """<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="nav">
<h2>MiniStudent</h2>
<a href="?page=logout">Logout</a>
</div>

<div class="container">
<div class="card">
<h2>Students</h2>

<input id="name" placeholder="Enter student">
<button onclick="addStudent()">Add</button>

<ul id="list">
<?php foreach($students as $s): ?>
<li><?= $s['name'] ?></li>
<?php endforeach; ?>
</ul>
</div>
</div>

<script src="assets/app.js"></script>
</body>
</html>
""",

"assets/app.js": """function addStudent(){
let name=document.getElementById("name").value;

fetch('?page=add',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:'name='+name
})
.then(r=>r.json())
.then(()=>{
let li=document.createElement("li");
li.innerText=name;
document.getElementById("list").appendChild(li);
});
}
""",

"assets/style.css": """*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:linear-gradient(135deg,#0f172a,#1e293b);color:#fff;min-height:100vh;}
.center{display:flex;justify-content:center;align-items:center;height:100vh;}
.nav{display:flex;justify-content:space-between;padding:20px;background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);}
.container{padding:30px;}
.card{padding:25px;border-radius:15px;background:rgba(255,255,255,0.05);backdrop-filter:blur(15px);box-shadow:0 8px 32px rgba(0,0,0,0.3);margin:auto;max-width:400px;}
input,button{width:100%;padding:12px;margin:10px 0;border:none;border-radius:8px;}
input{background:#1e293b;color:#fff;}
button{background:linear-gradient(45deg,#3b82f6,#06b6d4);color:#fff;cursor:pointer;}
button:hover{transform:scale(1.05);}
li{padding:10px;margin:5px 0;background:#1e293b;border-radius:8px;}
"""
}

# CREATE
for folder in folders:
    os.makedirs(os.path.join(BASE_DIR, folder), exist_ok=True)

for path, content in files.items():
    full_path = os.path.join(BASE_DIR, path)
    with open(full_path, "w", encoding="utf-8") as f:
        f.write(content)

print("✅ Files created inside current ministudents folder")