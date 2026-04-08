<?php 
$pageTitle = 'Mata Kuliah - SIAKAD Mini'; 
require_once 'includes/header.php'; 

// 1. Inisialisasi Session agar tidak error saat kosong
if (!isset($_SESSION['matakuliah'])) {
    $_SESSION['matakuliah'] = [];
}

// 2. Logika Tambah & Hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'tambah') {
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');
        $sks  = (int)($_POST['sks'] ?? 0);

        if ($kode && $nama && $sks > 0) {
            $_SESSION['matakuliah'][] = ['kode' => $kode, 'nama' => $nama, 'sks' => $sks];
        }
    }

    if ($_POST['action'] === 'hapus') {
        $idx = (int)$_POST['index'];
        if (isset($_SESSION['matakuliah'][$idx])) {
            array_splice($_SESSION['matakuliah'], $idx, 1);
            $_SESSION['matakuliah'] = array_values($_SESSION['matakuliah']);
        }
    }
    header('Location: matakuliah.php');
    exit;
}
?>

<div class="container">
    <a href="dashboard.php" class="btn btn-back">← Kembali</a>
    <div class="page-header"><h1>📚 Manajemen Mata Kuliah</h1></div>

    <div class="card">
        <h2 class="card-title">Tambah Mata Kuliah Baru</h2>
        <form method="POST" action="matakuliah.php">
            <input type="hidden" name="action" value="tambah">
            <div style="display: grid; grid-template-columns: 1fr 2fr 1fr auto; gap: 10px; align-items: end;">
                <div>
                    <label>Kode MK</label>
                    <input type="text" name="kode" required placeholder="BD101" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div>
                    <label>Nama Mata Kuliah</label>
                    <input type="text" name="nama" required placeholder="Nama MK" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <div>
                    <label>SKS</label>
                    <input type="number" name="sks" required min="1" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:5px;">
                </div>
                <button type="submit" class="btn btn-pink">Simpan</button>
            </div>
        </form>
    </div>

    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama MK</th>
                    <th>SKS</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['matakuliah'])): ?>
                    <?php foreach ($_SESSION['matakuliah'] as $i => $mk): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge badge-pink"><?= htmlspecialchars($mk['kode']) ?></span></td>
                        <td><?= htmlspecialchars($mk['nama']) ?></td>
                        <td><?= (int)$mk['sks'] ?> SKS</td>
                        <td style="text-align:center;">
                            <form method="POST" action="matakuliah.php" style="display:inline;">
                                <input type="hidden" name="action" value="hapus">
                                <input type="hidden" name="index" value="<?= $i ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">Data kosong.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>