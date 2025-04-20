<?php


class katalog {
    protected $kategori;
    protected $conn;

    public function __construct()
    {
        GLOBAL $conn;
        $this->conn = $conn;
    }

    public function getAll($kategori = 'NULL') {

if ($kategori){
    $kategori  = mysqli_real_escape_string($this->conn, $kategori);
    $query = "SELECT * FROM produk WHERE kategori = '$kategori' ";
} else {
    $query = "SELECT * FROM produk";
}
        $result =mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

}


?>
