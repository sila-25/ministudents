<?php
$students = $students ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | MiniStudent</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <nav class="glass-nav navbar">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i> MiniStudent
            </div>
            <div class="nav-actions">
                <span class="badge"><i class="fas fa-user-astronaut"></i> <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                <a href="index.php?page=logout" class="btn-outline btn" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>

        <div class="glass-card student-panel">
            <h2><i class="fas fa-users" style="color:#06b6d4;"></i> Student Roster</h2>
            <p style="margin-bottom: 1.5rem; opacity:0.7;">Manage enrolled students with simplicity</p>
            
            <div class="add-student-row">
                <div class="input-group" style="flex:2;">
                    <i class="fas fa-user-graduate input-icon"></i>
                    <input type="text" id="studentName" placeholder="Full name">
                </div>
                <div class="input-group" style="flex:2;">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="studentEmail" placeholder="Email address">
                </div>
                <button id="addStudentBtn" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add Student</button>
            </div>
            
            <div class="student-list" id="studentList">
                <?php if (empty($students)): ?>
                    <div class="empty-state">
                        <i class="fas fa-user-graduate"></i>
                        <p>No students yet. Add your first student above ✨</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($students as $student): ?>
                        <div class="student-item" data-id="<?= $student['id'] ?>">
                            <div class="student-info">
                                <div class="student-avatar"><?= strtoupper(substr($student['name'], 0, 1)) ?></div>
                                <div>
                                    <div class="student-name"><?= htmlspecialchars($student['name']) ?></div>
                                    <div class="student-email"><?= htmlspecialchars($student['email']) ?></div>
                                </div>
                            </div>
                            <button class="delete-btn" data-id="<?= $student['id'] ?>">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="assets/app.js"></script>
</body>
</html>