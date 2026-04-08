<?php
require_once __DIR__ . '/User.php';

class Mahasiswa extends User {
    private string $nim;
    private string $jurusan;
    private array $nilaiList = [];

    public function __construct(string $nim, string $nama, string $jurusan) {
        parent::__construct($nim, $nama, 'mahasiswa');
        $this->nim = $nim;
        $this->jurusan = $jurusan;
    }

    public function getNim(): string { return $this->nim; }
    public function getJurusan(): string { return $this->jurusan; }

    public function tambahNilai(string $mataKuliah, int $sks, string $huruf): void {
        $this->nilaiList[] = [
            'mataKuliah' => $mataKuliah,
            'sks' => $sks,
            'huruf' => $huruf
        ];
    }

    // FUNGSI INI YANG WAJIB ADA UNTUK MEMPERBAIKI ERROR LINE 56
    public function getTotalSKS(): int {
        $total = 0;
        foreach ($this->nilaiList as $n) {
            $total += $n['sks'];
        }
        return $total;
    }

    public function hitungIPK(): float {
        $totalBobot = 0;
        $totalSKS = $this->getTotalSKS();
        $map = ['A'=>4.0,'A-'=>3.7,'B+'=>3.3,'B'=>3.0,'B-'=>2.7,'C+'=>2.3,'C'=>2.0,'C-'=>1.7,'D'=>1.0,'E'=>0.0];
        
        foreach ($this->nilaiList as $n) {
            $bobot = $map[strtoupper($n['huruf'])] ?? 0.0;
            $totalBobot += $bobot * $n['sks'];
        }
        return $totalSKS > 0 ? round($totalBobot / $totalSKS, 2) : 0.0;
    }

    public function getInfo(): string {
        return "Mahasiswa: {$this->nama} | NIM: {$this->nim}";
    }

    public function cetakLaporan(): string {
        return "=== LAPORAN KHS ===\nNama: {$this->nama}\nNIM: {$this->nim}\nIPK: " . $this->hitungIPK();
    }
}