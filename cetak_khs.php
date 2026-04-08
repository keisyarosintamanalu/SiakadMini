<?php 
$pageTitle = 'Cetak KHS - SIAKAD Mini'; 
require_once 'includes/header.php'; 
require_once 'classes/Mahasiswa.php'; 

// Fungsi pembantu untuk mendapatkan bobot nilai (Internal cetak)
function getBobotKHS($huruf) {
    $map = ['A'=>4.0,'A-'=>3.7,'B+'=>3.3,'B'=>3.0,'B-'=>2.7,'C+'=>2.3,'C'=>2.0,'C-'=>1.7,'D'=>1.0,'E'=>0.0];
    return $map[strtoupper($huruf)] ?? 0.0;
}

$selectedNim = $_GET['nim'] ?? '';
$nilaiPerMhs = [];

// Kelompokkan data nilai berdasarkan NIM dari session
if (isset($_SESSION['nilai'])) {
    foreach ($_SESSION['nilai'] as $n) {
        $nilaiPerMhs[$n['nim']][] = $n;
    }
}
?>

<div class="container">
    <div class="no-print">
        <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
    </div>

    <div class="page-header">
        <h1>🖨️ Cetak Kartu Hasil Studi</h1>
        <p class="subtitle">Pilih mahasiswa untuk mencetak laporan nilai akhir</p>
    </div>

    <div class="card no-print" style="margin-bottom: 30px;">
        <form method="GET" action="cetak_khs.php" style="display: flex; gap: 15px; align-items: flex-end;">
            <div style="flex: 1;">
                <label style="display: block; margin-bottom: 5px; font-weight: bold;">Pilih Mahasiswa:</label>
                <select name="nim" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">-- Pilih Mahasiswa --</option>
                    <?php if (isset($_SESSION['mahasiswa'])): ?>
                        <?php foreach ($_SESSION['mahasiswa'] as $m): ?>
                            <option value="<?= $m['nim'] ?>" <?= $selectedNim == $m['nim'] ? 'selected' : '' ?>>
                                <?= $m['nim'] ?> - <?= htmlspecialchars($m['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-pink" style="padding: 10px 25px;">Tampilkan</button>
        </form>
    </div>

    <?php if ($selectedNim): ?>
        <?php
        // 1. Cari data profil mahasiswa di session
        $profil = null;
        if (isset($_SESSION['mahasiswa'])) {
            foreach ($_SESSION['mahasiswa'] as $m) {
                if ($m['nim'] === $selectedNim) {
                    $profil = $m;
                    break;
                }
            }
        }

        // 2. Jika profil ditemukan, proses tampilkan KHS
        if ($profil): ?>
            <?php
            // PERBAIKAN FATAL ERROR: Pastikan variabel jurusan tidak NULL
            // Kita ambil dari key 'prodi' sesuai inputan kamu di mahasiswa.php
            $prodi = $profil['prodi'] ?? $profil['jurusan'] ?? 'Program Studi';

            // Inisialisasi Object Mahasiswa (OOP)
            $mhsObj = new Mahasiswa($profil['nim'], $profil['nama'], $prodi);

            // Masukkan nilai-nilai dari session ke dalam Object
            if (isset($nilaiPerMhs[$selectedNim])) {
                foreach ($nilaiPerMhs[$selectedNim] as $v) {
                    $mhsObj->tambahNilai($v['nama_mk'], (int)$v['sks'], $v['nilai']);
                }
            }

            $totalSKS = $mhsObj->getTotalSKS();
            $ipk = $mhsObj->hitungIPK();
            ?>

            <div class="card khs-container" id="printArea">
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ec4899; padding-bottom: 10px;">
                    <h2 style="margin: 0; color: #ec4899;">KARTU HASIL STUDI (KHS)</h2>
                    <p style="margin: 5px 0;">Sistem Informasi Akademik Mini</p>
                </div>

                <div style="display: grid; grid-template-columns: 120px auto; gap: 5px; margin-bottom: 20px; font-weight: 500;">
                    <div>NIM</div><div>: <?= $profil['nim'] ?></div>
                    <div>Nama</div><div>: <?= htmlspecialchars($profil['nama']) ?></div>
                    <div>Prodi</div><div>: <?= htmlspecialchars($prodi) ?></div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Nilai</th>
                            <th>Bobot</th>
                            <th>SKS x Bobot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($nilaiPerMhs[$selectedNim])): ?>
                            <?php foreach ($nilaiPerMhs[$selectedNim] as $i => $n): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($n['nama_mk']) ?></td>
                                    <td><?= $n['sks'] ?></td>
                                    <td><strong><?= $n['nilai'] ?></strong></td>
                                    <td><?= getBobotKHS($n['nilai']) ?></td>
                                    <td><?= $n['sks'] * getBobotKHS($n['nilai']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center;">Belum ada data nilai.</td></tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #fdf2f8; font-weight: bold;">
                            <td colspan="2" style="text-align: right;">Total SKS:</td>
                            <td><?= $totalSKS ?></td>
                            <td colspan="2" style="text-align: right;">IPK:</td>
                            <td><?= number_format($ipk, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>

                <div style="margin-top: 30px; padding: 15px; background: #f8fafc; border-radius: 8px; border-left: 4px solid #ec4899;">
                    <h4 style="margin-top: 0;">💬 Pesan Sistem (OOP Output):</h4>
                    <p style="font-family: monospace; margin-bottom: 0;"><?= $mhsObj->cetakLaporan() ?></p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 20px;" class="no-print">
                <button onclick="window.print()" class="btn btn-pink" style="padding: 12px 30px; font-size: 16px; cursor: pointer;">
                    🖨️ Cetak KHS ke PDF / Printer
                </button>
            </div>

        <?php else: ?>
            <div class="alert alert-error">Data mahasiswa tidak ditemukan dalam sistem.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .card { border: none; box-shadow: none; }
        .khs-container { padding: 0; }
    }
</style>

<?php require_once 'includes/header.php'; ?>