<?php $pageTitle = 'Dashboard - SIAKAD Mini'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="container">
    <div class="page-header text-center">
        <div class="header-icon">🎓</div>
        <h1>Dashboard SIAKAD</h1>
        <p class="subtitle">Sistem Informasi Akademik Mini — Bisnis Digital</p>
    </div>

    <div class="card-grid">
        <a href="mahasiswa.php" class="menu-card">
            <div class="menu-icon" style="background: linear-gradient(135deg, #ec4899, #f43f5e);">👨‍🎓</div>
            <h3>Manajemen Mahasiswa</h3>
            <p>Kelola data mahasiswa</p>
        </a>
        <a href="matakuliah.php" class="menu-card">
            <div class="menu-icon" style="background: linear-gradient(135deg, #d946ef, #ec4899);">📚</div>
            <h3>Manajemen Mata Kuliah</h3>
            <p>Kelola data mata kuliah</p>
        </a>
        <a href="input_nilai.php" class="menu-card">
            <div class="menu-icon" style="background: linear-gradient(135deg, #f43f5e, #fb7185);">📝</div>
            <h3>Input Nilai</h3>
            <p>Input nilai mahasiswa</p>
        </a>
        <a href="hitung_ipk.php" class="menu-card">
            <div class="menu-icon" style="background: linear-gradient(135deg, #fb7185, #d946ef);">🧮</div>
            <h3>Hitung IPK</h3>
            <p>Hitung IPK mahasiswa</p>
        </a>
        <a href="cetak_khs.php" class="menu-card">
            <div class="menu-icon" style="background: linear-gradient(135deg, #d946ef, #f43f5e);">🖨️</div>
            <h3>Cetak KHS</h3>
            <p>Cetak kartu hasil studi</p>
        </a>
    </div>

    <!-- Demo OOP Polymorphism -->
    <div class="card mt-30">
        <h2 class="card-title">📋 Demo OOP - Polymorphism</h2>
        <p class="subtitle mb-15">Menunjukkan bagaimana method getInfo() dan cetakLaporan() berbeda di setiap class</p>
        <?php
        require_once 'classes/Mahasiswa.php';
        require_once 'classes/Dosen.php';

        $mhs = new Mahasiswa('2024001', 'Andi Pratama', 'Bisnis Digital');
        $dsn = new Dosen('D001', 'Dr. Siti Aminah', 'Pemrograman Web');

        // Polymorphism: method yang sama, output berbeda
        $users = [$mhs, $dsn];
        ?>
        <div class="oop-demo-grid">
            <?php foreach ($users as $u): ?>
                <div class="oop-demo-card">
                    <span class="badge <?= $u->getRole() === 'mahasiswa' ? 'badge-pink' : 'badge-purple' ?>">
                        <?= ucfirst($u->getRole()) ?>
                    </span>
                    <p class="oop-info"><?= htmlspecialchars($u->getInfo()) ?></p>
                    <pre class="oop-report"><?= htmlspecialchars($u->cetakLaporan()) ?></pre>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
