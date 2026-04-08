<?php require_once __DIR__ . '/session.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SIAKAD Mini' ?></title>
    
    <link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="dashboard.php" class="nav-brand">🎓 SIAKAD Mini</a>
            <div class="nav-links">
                <a href="dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
                <a href="mahasiswa.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'mahasiswa.php' ? 'active' : '' ?>">Mahasiswa</a>
                <a href="matakuliah.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'matakuliah.php' ? 'active' : '' ?>">Mata Kuliah</a>
                <a href="input_nilai.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'input_nilai.php' ? 'active' : '' ?>">Input Nilai</a>
                <a href="hitung_ipk.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'hitung_ipk.php' ? 'active' : '' ?>">Hitung IPK</a>
                <a href="cetak_khs.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'cetak_khs.php' ? 'active' : '' ?>">Cetak KHS</a>
            </div>
            <div class="nav-user">
                <span class="nav-username">Halo, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </nav>
    <main class="main-content">