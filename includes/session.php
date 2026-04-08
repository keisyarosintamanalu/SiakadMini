<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Inisialisasi data session jika belum ada
if (!isset($_SESSION['mahasiswa'])) {
    $_SESSION['mahasiswa'] = [
        ['nim' => '2024001', 'nama' => 'Andi Pratama', 'jurusan' => 'Bisnis Digital'],
        ['nim' => '2024002', 'nama' => 'Siti Nurhaliza', 'jurusan' => 'Bisnis Digital'],
        ['nim' => '2024003', 'nama' => 'Budi Santoso', 'jurusan' => 'Bisnis Digital'],
    ];
}

if (!isset($_SESSION['matakuliah'])) {
    $_SESSION['matakuliah'] = [
        ['kode' => 'BD101', 'nama' => 'Pemrograman Web', 'sks' => 3],
        ['kode' => 'BD102', 'nama' => 'Basis Data', 'sks' => 3],
        ['kode' => 'BD103', 'nama' => 'Algoritma & Pemrograman', 'sks' => 4],
    ];
}

if (!isset($_SESSION['nilai'])) {
    $_SESSION['nilai'] = [];
}
