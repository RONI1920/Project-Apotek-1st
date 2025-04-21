<?php
require_once "../pages/template.header.php";

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Apotek</title>
    <link rel="stylesheet" href="../css/css-laporan-penjualan.css">
</head>
<body>

    <h2>Laporan Penjualan Apotek</h2>
    <h4>Periode: Semua Data</h4>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Referensi</th>
                <th>No. Pesanan</th>
                <th>Mata Uang</th>
                <th>Sub Total</th>
                <th>Diskon</th>
                <th>Pajak</th>
                <th>Total Penjualan</th>
                <th>Pembayaran</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM penjualan ORDER BY tanggal ASC");

            $total_sub = $total_diskon = $total_pajak = $total_total = $total_bayar = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $sub = $row['sub_total'];
                    $diskon = $row['diskon'];
                    $pajak = $row['pajak'];
                    $total = $row['total_harga'];
                    $bayar = $row['pembayaran'];
                    $saldo = $total - $bayar;

                    echo "<tr>
                        <td>{$row['tanggal']}</td>
                        <td>REF-".str_pad($row['id'], 4, '0', STR_PAD_LEFT)."</td>
                        <td>INV-".str_pad($row['id'], 3, '0', STR_PAD_LEFT)."</td>
                        <td>IDR</td>
                        <td>".number_format($sub)."</td>
                        <td>".number_format($diskon)."</td>
                        <td>".number_format($pajak)."</td>
                        <td>".number_format($total)."</td>
                        <td>".number_format($bayar)."</td>
                        <td>".number_format($saldo)."</td>
                    </tr>";

                    $total_sub += $sub;
                    $total_diskon += $diskon;
                    $total_pajak += $pajak;
                    $total_total += $total;
                    $total_bayar += $bayar;
                }
            } else {
                echo '<tr><td colspan="10">Tidak ada data penjualan</td></tr>';
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">TOTAL</td>
                <td><?= number_format($total_sub) ?></td>
                <td><?= number_format($total_diskon) ?></td>
                <td><?= number_format($total_pajak) ?></td>
                <td><?= number_format($total_total) ?></td>
                <td><?= number_format($total_bayar) ?></td>
                <td><?= number_format($total_total - $total_bayar) ?></td>
            </tr>
        </tfoot>
    </table>

    
    <div class="buttons">
        <a href="cetak_laporan.php" target="_blank" class="cetak">🖨️ Cetak PDF</a>
        <a href="admin-aksi.php" class="kembali">← Kembali</a>
    </div>

</body>
</html>








































