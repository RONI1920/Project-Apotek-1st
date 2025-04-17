<?php

require_once '../config/config.php';


class katalog {
    protected $conn;
    protected $kategori;

    public function __construct($conn, $kategori = ''){
        $this->conn = $conn;
        $this->kategori = $kategori;
    }
    public function getAll() {
        $query = "SELECT * FROM produk WHERE kategori = '" . mysqli_real_escape_string($this->conn, $this->kategori) . "'";
    
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

}


?>
