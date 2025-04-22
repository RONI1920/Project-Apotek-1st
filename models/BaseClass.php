<?php

// Koneksi Database
if (!class_exists("Database")) {
    class Database
    {
        protected $conn;

        public function __construct($host = 'localhost', $user = 'root', $pass = 192022, $dbname = 'apotek_roni')
        {
            $this->conn = new mysqli($host, $user, $pass, $dbname);

            // Tambahan pengecekan error koneksi (opsional tapi disarankan)
            if ($this->conn->connect_error) {
                die("Koneksi database gagal: " . $this->conn->connect_error);
            }
        }

        public function get_connect_to_data_base()
        {
            return $this->conn;
        }
    }
}

// Produk Repository - Berhubungan dengan Database
class ProdukRepository
{
    protected $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function get_all($kategori = null)
    {
        if ($kategori) {
            $kategori = mysqli_real_escape_string($this->conn, $kategori);
            $query = "SELECT * FROM produk WHERE kategori = '$kategori' ";
        } else {
            $query = "SELECT * FROM produk";
        }

        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        return $data;
    }

    // Mengambil total produk untuk keperluan pagination
    public function get_total_produk($search = '')
    {
        $search = mysqli_real_escape_string($this->conn, $search);
        if (!empty($search)) {
            $query = "SELECT COUNT(*) as total FROM produk WHERE nama_produk LIKE '%$search%'";
        } else {
            $query = "SELECT COUNT(*) as total FROM produk";
        }

        $result = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    // Mengambil produk berdasarkan pagination dan pencarian
    public function get_total_pagination($limit, $offset, $search = '')
    {
        $search = mysqli_real_escape_string($this->conn, $search);

        $limit = (int) $limit;
        $offset = (int) $offset;

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

    // Mengambil produk berdasarkan ID
    public function get_by_id($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}

// Layanan Keranjang
class KeranjangService
{
    protected $produkRepo;

    public function __construct(ProdukRepository $produkRepo)
    {
        $this->produkRepo = $produkRepo;

        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }
    }

    // Menambahkan produk ke dalam keranjang
    public function tambahProdukKeKeranjang($idProduk)
    {
        $produk = $this->produkRepo->get_by_id($idProduk);

        if (!$produk) {
            return false;
        }

        if (!isset($_SESSION['keranjang'][$idProduk])) {
            $_SESSION['keranjang'][$idProduk] = [
                'nama' => $produk['nama_produk'],
                'harga' => $produk['harga'],
                'jumlah' => 1,
                'gambar' => $produk['gambar']
            ];
        } else {
            $_SESSION['keranjang'][$idProduk]['jumlah']++;
            // Kirimkan status untuk notifikasi
            $_SESSION['status'] = 'updated'; // Status untuk produk yang sudah ada di keranjang
        }
    }

    // Mendapatkan isi keranjang
    public function get_isi_Keranjang()
    {
        return $_SESSION['keranjang'];
    }

    // Menghapus produk dari keranjang
    public function hapusProdukDariKeranjang($idProduk)
    {
        if (isset($_SESSION['keranjang'][$idProduk])) {
            unset($_SESSION['keranjang'][$idProduk]);
        }
    }
}

// Produk Controller
class ProdukController
{
    protected $produkRepo;
    protected $keranjangService;

    public function __construct($produkRepo, $keranjangService)
    {
        $this->produkRepo = $produkRepo;
        $this->keranjangService = $keranjangService;
    }

    // Menangani permintaan produk
    public function handleRequest()
    {
        if (isset($_GET['add'])) {
            $id = (int)$_GET['add'];
            $this->keranjangService->tambahProdukKeKeranjang($id);
            $this->set_status('success'); // Set status sukses
            header('Location: ' . $_SERVER['HTTP_REFERER']); // Redirect untuk menghindari pengiriman berulang
            exit;
        }

        if (isset($_GET['remove'])) {
            $id = (int)$_GET['remove'];
            $this->keranjangService->hapusProdukDariKeranjang($id);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Tampilkan produk berdasarkan kategori atau tanpa kategori
        $kategori = $_GET['kategori'] ?? null;
        $produk = $this->produkRepo->get_all($kategori);

        // Mengembalikan produk ke tampilan
        return $produk;
    }

    // Menyimpan status ke session
    public function set_status($status)
    {
        $_SESSION['status'] = $status;
    }

    // Mengambil status dari session
    public function get_status()
    {
        return isset($_SESSION['status']) ? $_SESSION['status'] : null;
    }

    // Menghapus status dari session
    public function clear_status()
    {
        unset($_SESSION['status']);
    }
}
