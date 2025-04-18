<?php


class katalog {
    protected $kategori;

    public function getAll($kategori) {
        GLOBAL $conn;
        $result =mysqli_query($conn, "SELECT * FROM produk WHERE kategori = '$kategori'");
    
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

}


?>
