<?php
class User {
    protected string $username;
    protected string $nama;
    protected string $role;

    public function __construct(string $username, string $nama, string $role) {
        $this->username = $username;
        $this->nama = $nama;
        $this->role = $role;
    }

    public function getNama(): string { return $this->nama; }
    public function getRole(): string { return $this->role; }
}