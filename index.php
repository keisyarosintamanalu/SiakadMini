<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Data akun demo
    $accounts = [
        ['username' => 'admin', 'password' => 'admin123', 'name' => 'Administrator', 'role' => 'admin'],
        ['username' => 'dosen', 'password' => 'dosen123', 'name' => 'Dosen', 'role' => 'dosen'],
    ];

    $found = false;
    foreach ($accounts as $acc) {
        if ($acc['username'] === $username && $acc['password'] === $password) {
            $_SESSION['user'] = [
                'name' => $acc['name'],
                'role' => $acc['role'],
            ];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Username atau password salah!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIAKAD Mini</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-icon">🎓</div>
            <h1 class="login-title">SIAKAD Mini</h1>
            <p class="login-subtitle">Sistem Informasi Akademik</p>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-pink btn-full">Login</button>
            </form>

            <div class="demo-box">
                <p class="demo-title">Demo Accounts:</p>
                <p>Admin: <strong>admin / admin123</strong></p>
                <p>Dosen: <strong>dosen / dosen123</strong></p>
            </div>
        </div>
    </div>
</body>
</html>