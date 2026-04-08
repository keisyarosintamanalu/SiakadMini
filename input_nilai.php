<?php 
$pageTitle = 'Input Nilai - SIAKAD Mini'; 
require_once 'includes/header.php'; 

// Fungsi pembantu untuk konversi nilai ke bobot (Didefinisikan di awal agar aman)
function getBobot($huruf) {
    $map = [
        'A'  => 4.0, 'A-' => 3.7, 
        'B+' => 3.3, 'B'  => 3.0, 'B-' => 2.7, 
        'C+' => 2.3, 'C'  => 2.0, 'C-' => 1.7, 
        'D'  => 1.0, 'E'  => 0.0
    ];
    return $map[strtoupper($huruf)] ?? 0.0;
}

$pesan = '';

// Proses Simpan Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'] ?? '';
    $kode_mk = $_POST['kode_mk'] ?? '';
    $nilai = strtoupper(trim($_POST['nilai'] ?? ''));

    $validNilai = ['A','A-','B+','B','B-','C+','C','C-','D','E'];

    if ($nim && $kode_mk && in_array($nilai, $validNilai)) {
        // 1. Cari nama mahasiswa
        $namaMhs = '';
        foreach ($_SESSION['mahasiswa'] as $m) {
            if ($m['nim'] === $nim) { 
                $namaMhs = $m['nama']; 
                break; 
            }
        }

        // 2. Cari nama mata kuliah & SKS
        $namaMK = '';
        $sksMK = 0;
        foreach ($_SESSION['matakuliah'] as $mk) {
            if ($mk['kode'] === $kode_mk) { 
                $namaMK = $mk['nama']; 
                $sksMK = $mk['sks']; 
                break; 
            }
        }

        if ($namaMhs && $namaMK) {
            // 3. Cek apakah nilai untuk NIM & MK ini sudah ada (Update jika ada)
            $exists = false;
            if (!isset($_SESSION['nilai'])) {
                $_SESSION['nilai'] = [];
            }

            foreach ($_SESSION['nilai'] as &$n) {
                if ($n['nim'] === $nim && $n['kode_mk'] === $kode_mk) {
                    $n['nilai'] = $nilai;
                    $exists = true;
                    break;
                }
            }
            unset($n); // Penting untuk memutus referensi foreach

            // 4. Jika belum ada, tambah data baru
            if (!$exists) {
                $_SESSION['nilai'][] = [
                    'nim'      => $nim,
                    'nama_mhs' => $namaMhs,
                    'kode_mk'  => $kode_mk,
                    'nama_mk'  => $namaMK,
                    'sks'      => $sksMK,
                    'nilai'    => $nilai,
                ];
            }
            $pesan = "✅ Nilai untuk " . htmlspecialchars($namaMhs) . " berhasil disimpan!";
        } else {
            $pesan = "❌ Mahasiswa atau Mata Kuliah tidak ditemukan.";
        }
    } else {
        $pesan = "❌ Data tidak valid. Pastikan semua field terisi.";
    }
}
?>

<div class="container">
    <a href="dashboard.php" class="btn btn-back">← Kembali ke Dashboard</a>

    <div class="page-header">
        <h1>📝 Input Nilai Mahasiswa</h1>
        <p class="subtitle">Masukkan nilai untuk setiap mahasiswa per mata kuliah</p>
    </div>

    <?php if ($pesan): ?>
        <div class="alert <?= strpos($pesan, '✅') !== false ? 'alert-success' : 'alert-error' ?>">
            <?= $pesan ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h2 class="card-title">Form Input Nilai</h2>
        <form method="POST" class="form-inline-grid">
            <div class="form-group">
                <label>Mahasiswa</label>
                <select name="nim" required>
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php if (isset($_SESSION['mahasiswa'])): ?>
                        <?php foreach ($_SESSION['mahasiswa'] as $m): ?>
                            <option value="<?= $m['nim'] ?>"><?= $m['nim'] ?> - <?= htmlspecialchars($m['nama']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Mata Kuliah</label>
                <select name="kode_mk" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php if (isset($_SESSION['matakuliah'])): ?>
                        <?php foreach ($_SESSION['matakuliah'] as $mk): ?>
                            <option value="<?= $mk['kode'] ?>"><?= $mk['kode'] ?> - <?= htmlspecialchars($mk['nama']) ?> (<?= $mk['sks'] ?> SKS)</option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nilai</label>
                <select name="nilai" required>
                    <option value="">-- Pilih --</option>
                    <?php foreach (['A','A-','B+','B','B-','C+','C','C-','D','E'] as $n): ?>
                        <option value="<?= $n ?>"><?= $n ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group form-btn-group" style="align-self: flex-end;">
                <button type="submit" class="btn btn-pink">💾 Simpan Nilai</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2 class="card-title">Data Nilai Terinput (<?= isset($_SESSION['nilai']) ? count($_SESSION['nilai']) : 0 ?>)</h2>
        <?php if (empty($_SESSION['nilai'])): ?>
            <p class="text-muted text-center">Belum ada nilai yang diinput.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['nilai'] as $i => $n): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($n['nim']) ?></td>
                            <td><?= htmlspecialchars($n['nama_mhs']) ?></td>
                            <td><?= htmlspecialchars($n['nama_mk']) ?></td>
                            <td><?= $n['sks'] ?></td>
                            <td><span class="badge badge-pink"><?= $n['nilai'] ?></span></td>
                            <td><strong><?= number_format(getBobot($n['nilai']), 1) ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>