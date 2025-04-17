<?php

require_once '../config/config.php';


class obat {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM produk WHERE kategori = 'obat'"; 
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        } 
        return $data;
    }
}


$obatKategori =  new obat($conn); // koneksi dikirim
$data_obat = $obatKategori->getAll(); // ambil data dari database

?>
