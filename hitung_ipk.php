<?php 
$pageTitle = 'Hitung IPK - SIAKAD Mini'; 
require_once 'includes/header.php'; 
require_once 'classes/Mahasiswa.php'; // WAJIB panggil yang di folder classes

$nilaiPerMhs = [];
if (isset($_SESSION['nilai'])) {
    foreach ($_SESSION['nilai'] as $n) { $nilaiPerMhs[$n['nim']][] = $n; }
}
?>

<div class="container">
    <div class="page-header"><h1>🧮 Hitung IPK Mahasiswa</h1></div>
    <div class="card-grid">
        <?php foreach ($nilaiPerMhs as $nim => $nilaiArr): ?>
            <?php
            $mhsObj = new Mahasiswa($nim, $nilaiArr[0]['nama_mhs'], 'Bisnis Digital');
            foreach ($nilaiArr as $nv) { $mhsObj->tambahNilai($nv['nama_mk'], $nv['sks'], $nv['nilai']); }
            ?>
            <div class="card">
                <h3><?= htmlspecialchars($mhsObj->getNama()) ?></h3>
                <p>IPK: <strong><?= $mhsObj->hitungIPK() ?></strong></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>