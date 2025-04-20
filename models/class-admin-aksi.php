<?php

class Produk
{
    protected $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Mengambil total produk untuk keperluan pagination
    public function get_total_produk($search = '')
    {
        $search = mysqli_real_escape_string($this->conn, $search);

        if (!empty($search)) {
            $query = "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE '%$search%'";
        } else {
            $query = "SELECT COUNT(*) as total FROM produk";
            $result = mysqli_query($this->conn, $query);
            $row = mysqli_fetch_assoc($result);
        }
        return $row['total'] ?? 0;
    }

    // Mengambil produk berdasarkan pagination dan pencarian
    public function get_total_pagination($limit, $offset, $search = '')
    {
        $search = mysqli_real_escape_string($this->conn, $search);

        if (!empty($search)) {
            $query = "SELECT * FROM produk WHERE nama_produk LIKE '%$search%' LIMIT $limit OFFSET $offset";
        } else {
            $query = "SELECT * FROM produk LIMIT $limit OFFSET $offset";
        }
        $result = mysqli_query($this->conn, $query);
        $produkList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $produkList[] = $row;
        }
        return $produkList;
    }
}
