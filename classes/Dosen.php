class Dosen extends User {
    private string $nip;

    public function __construct(string $nip, string $nama) {
        parent::__construct($nip, $nama, 'dosen'); // sudah benar
        $this->nip = $nip;
    }

    public function getInfo(): string {
        return "Dosen: {$this->nama} | NIP: {$this->nip}";
    }
}