<?php 
$pageTitle = 'Manajemen Mahasiswa - SIAKAD Mini'; 
require_once 'includes/header.php'; 

// Inisialisasi session mahasiswa jika belum ada
if (!isset($_SESSION['mahasiswa'])) {
    $_SESSION['mahasiswa'] = [];
}

// --- LOGIKA CRUD ---

// 1. Tambah & Edit Data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $nim      = trim($_POST['nim']);
    $nama     = trim($_POST['nama']);
    $prodi    = trim($_POST['prodi']);
    $semester = trim($_POST['semester']);

    if ($_POST['action'] === 'simpan') {
        if (isset($_POST['edit_index']) && $_POST['edit_index'] !== "") {
            // Logika Edit
            $index = $_POST['edit_index'];
            $_SESSION['mahasiswa'][$index] = [
                'nim' => $nim, 'nama' => $nama, 'prodi' => $prodi, 'semester' => $semester
            ];
        } else {
            // Logika Tambah
            $_SESSION['mahasiswa'][] = [
                'nim' => $nim, 'nama' => $nama, 'prodi' => $prodi, 'semester' => $semester
            ];
        }
    }
    header("Location: mahasiswa.php");
    exit;
}

// 2. Hapus Data
if (isset($_GET['hapus'])) {
    $index = $_GET['hapus'];
    array_splice($_SESSION['mahasiswa'], $index, 1);
    header("Location: mahasiswa.php");
    exit;
}

// 3. Ambil data untuk Edit (Trigger Form)
$editData = null;
$editIndex = "";
if (isset($_GET['edit'])) {
    $editIndex = $_GET['edit'];
    $editData = $_SESSION['mahasiswa'][$editIndex];
}
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <a href="dashboard.php" class="btn-back">← Kembali</a>
            <h1 style="margin-top: 10px;">Manajemen Mahasiswa</h1>
        </div>
        <button class="btn btn-pink" onclick="document.getElementById('form-mhs').scrollIntoView();">
            + Tambah Mahasiswa
        </button>
    </div>

    <div class="card" id="form-mhs" style="margin-bottom: 30px;">
        <h3 class="card-title"><?= $editData ? '📝 Edit Mahasiswa' : '➕ Tambah Mahasiswa' ?></h3>
        <form method="POST" action="mahasiswa.php">
            <input type="hidden" name="action" value="simpan">
            <input type="hidden" name="edit_index" value="<?= $editIndex ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="text" name="nim" placeholder="NIM" value="<?= $editData['nim'] ?? '' ?>" required>
                <input type="text" name="nama" placeholder="Nama Lengkap" value="<?= $editData['nama'] ?? '' ?>" required>
                <input type="text" name="prodi" placeholder="Program Studi" value="<?= $editData['prodi'] ?? '' ?>" required>
                <input type="number" name="semester" placeholder="Semester" value="<?= $editData['semester'] ?? '' ?>" required>
            </div>
            
            <div style="margin-top: 15px;">
                <button type="submit" class="btn btn-pink">Simpan Data</button>
                <?php if($editData): ?>
                    <a href="mahasiswa.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Semester</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($_SESSION['mahasiswa'])): ?>
                    <?php foreach ($_SESSION['mahasiswa'] as $i => $m): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($m['nim']) ?></strong></td>
                        <td><?= htmlspecialchars($m['nama']) ?></td>
                        <td><?= htmlspecialchars($m['prodi']) ?></td>
                        <td>Semester <?= htmlspecialchars($m['semester']) ?></td>
                        <td style="text-align: center;">
                            <a href="mahasiswa.php?edit=<?= $i ?>" class="btn-icon btn-edit" title="Edit">✏️</a>
                            <a href="mahasiswa.php?hapus=<?= $i ?>" class="btn-icon btn-delete" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #999;">
                            Belum ada data mahasiswa. Silakan tambah data di atas.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* CSS Tambahan untuk menyamakan dengan Gambar 2 */
.btn-icon {
    text-decoration: none;
    padding: 5px 8px;
    border-radius: 5px;
    margin: 0 2px;
    display: inline-block;
}
.btn-edit { background: #fdf2f8; border: 1px solid #f9a8d4; }
.btn-delete { background: #fff1f2; border: 1px solid #fecdd3; }

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #f8fafc;
}

.data-table thead {
    background-color: #ec4899;
    color: white;
}
</style>

<?php require_once 'includes/footer.php'; ?>