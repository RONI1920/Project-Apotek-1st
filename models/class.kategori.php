<?php


class katalog {
    protected $kategori;
    protected $conn;

    public function __construct()
    {
        GLOBAL $conn;
        $this->conn = $conn;
    }

    public function get_all($kategori = 'NULL') {

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


// apa yang gw lakuin?
// gw membuat 1 file Repositori. dimana file ini melakukan suatu logic yang bisa mudah dibaca,
// ringkas dan perawatan nya mudah. gw gak perlu repeat penulisan coding, menerapkan konsep OOP PHP.
//memisahkan fungsi dimana fungsi melakukan tugas nya sendiri.
//mulai mencoba memisahkan antara models, controller dan views




