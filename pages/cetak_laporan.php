<?php
session_start();
require_once __DIR__ . '/../fpdf/fpdf.php';
include('../config/config.php');

// PDF Setup
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Header
$pdf->Cell(0, 10, 'Laporan Penjualan Apotek', 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 7, 'Periode: Semua Data', 0, 1, 'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(100, 149, 237);
$pdf->SetTextColor(255);
$headers = ['Tanggal', 'Ref', 'No. Pesanan', 'Mata Uang', 'Sub Total', 'Diskon', 'Pajak', 'Total', 'Bayar', 'Saldo'];
$widths = [28, 25, 35, 25, 28, 28, 25, 30, 30, 28];

foreach ($headers as $i => $header) {
    $pdf->Cell($widths[$i], 8, $header, 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0);

$query = $conn->query("SELECT * FROM penjualan ORDER BY tanggal ASC");

$total_sub = $total_diskon = $total_pajak = $total_all = $total_bayar = 0;
$hasData = false;

while ($row = $query->fetch_assoc()) {
    $hasData = true;

    $tanggal = $row['tanggal'];
    $ref = str_pad($row['id'], 5, '0', STR_PAD_LEFT);
    $no_pesanan = 'INV-' . str_pad($row['id'], 3, '0', STR_PAD_LEFT);
    $mata_uang = 'IDR';
    $sub = $row['sub_total'];
    $diskon = $row['diskon'];
    $pajak = $row['pajak'];
    $total = $row['total_harga'];
    $bayar = $row['pembayaran'];
    $saldo = $total - $bayar;

    $values = [$tanggal, $ref, $no_pesanan, $mata_uang, $sub, $diskon, $pajak, $total, $bayar, $saldo];

    foreach ($values as $i => $val) {
        $align = is_numeric($val) && $i > 3 ? 'R' : 'C';
        $pdf->Cell($widths[$i], 8, number_format($val), 1, 0, $align);
    }
    $pdf->Ln();

    $total_sub += $sub;
    $total_diskon += $diskon;
    $total_pajak += $pajak;
    $total_all += $total;
    $total_bayar += $bayar;
}

if (!$hasData) {
    $pdf->Cell(array_sum($widths), 8, 'Tidak ada data penjualan.', 1, 1, 'C');
} else {
    $saldo_total = $total_all - $total_bayar;

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell($widths[0] + $widths[1] + $widths[2] + $widths[3], 8, 'TOTAL', 1, 0, 'C');
    $totals = [$total_sub, $total_diskon, $total_pajak, $total_all, $total_bayar, $saldo_total];

    for ($i = 0; $i < count($totals); $i++) {
        $pdf->Cell($widths[$i + 4], 8, number_format($totals[$i]), 1, 0, 'R');
    }
    $pdf->Ln();
}

$pdf->SetY(-15);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, 'Halaman: ' . $pdf->PageNo(), 0, 0, 'C');

$pdf->Output();
?>
---

Kalau kamu sudah **isi beberapa data penjualan** di database, nanti hasilnya akan muncul otomatis.

Perlu aku bantu buat script **input dummy data penjualan** juga biar kamu bisa coba langsung?
