<?php

require_once '../config/config.php';


class katalog {
    public $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM produk WHERE kategori = '' "; 
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        } 
        return $data;
    }
}


class obat extends katalog{
    public function __construct($db) {
        parent :: __construct($db);
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




class vitamin extends katalog{
    public function __construct($db) {
        parent :: __construct($db);
    }

    public function getAll() {
        $query = "SELECT * FROM produk WHERE kategori = 'vitamin'"; 
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        } 
        return $data;
    }
}


class alat extends katalog{
    public function __construct($db) {
        parent :: __construct($db);
    }

    public function getAll() {
        $query = "SELECT * FROM produk WHERE kategori = 'alat'"; 
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        } 
        return $data;
    }
}


?>
