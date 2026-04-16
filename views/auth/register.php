<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | MiniStudent</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="glass-card auth-card">
            <div class="logo-glow" style="font-size: 2.4rem; text-align: center;">Join MiniStudent</div>
            <p style="color: #9bb6e0; text-align: center; margin-bottom: 2rem;">Start managing students with style</p>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <form id="registerForm" method="POST" action="index.php?page=register">
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="name" placeholder="Full name" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Email address" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Password (min 4 chars)" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Get Started <i class="fas fa-user-plus"></i></button>
            </form>
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="index.php?page=login" class="btn-outline btn">← Back to Login</a>
            </div>
        </div>
    </div>
    <script src="assets/app.js"></script>
</body>
</html>