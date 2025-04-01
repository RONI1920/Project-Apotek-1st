<?php
require_once 'fpdf/fpdf.php';
include "config.php";

// Buat objek PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Laporan Penjualan Apotek', 0, 1, 'C');
$pdf->Ln(10); // Spasi setelah judul

// Lebar total tabel
$lebar_tabel = 10 + 60 + 20 + 30 + 40; // Total 160 mm
$pos_x = ($pdf->GetPageWidth() - $lebar_tabel) / 2; // Hitung posisi tengah

$pdf->SetX($pos_x); // Posisikan ke tengah sebelum membuat header

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(50, 150, 250); // Warna biru muda
$pdf->SetTextColor(255, 255, 255); // Warna putih untuk teks
$pdf->SetDrawColor(0, 0, 0); // Warna garis border

// Buat Header
$pdf->Cell(10, 7, 'No', 1, 0, 'C', true);
$pdf->Cell(60, 7, 'Nama Obat', 1, 0, 'C', true);
$pdf->Cell(20, 7, 'Jumlah', 1, 0, 'C', true);
$pdf->Cell(30, 7, 'Total Harga', 1, 0, 'C', true);
$pdf->Cell(40, 7, 'Tanggal', 1, 1, 'C', true);

// Kembalikan warna teks ke hitam
$pdf->SetTextColor(0, 0, 0);

// Ambil data penjualan dari database
$query = $conn->query("
    SELECT penjualan.id, obat.nama, penjualan.jumlah, penjualan.total_harga, penjualan.tanggal 
    FROM penjualan 
    JOIN obat ON penjualan.obat_id = obat.id
");

$no = 1;
$pdf->SetFont('Arial', '', 10);
while ($row = $query->fetch_assoc()) {
    $pdf->SetX($pos_x); // Pastikan setiap baris tabel tetap di tengah
    $pdf->Cell(10, 7, $no++, 1, 0, 'C');
    $pdf->Cell(60, 7, $row["nama"], 1, 0, 'C');
    $pdf->Cell(20, 7, $row["jumlah"], 1, 0, 'C');
    $pdf->Cell(30, 7, 'Rp. ' . number_format($row["total_harga"]), 1, 0, 'C');
    $pdf->Cell(40, 7, $row["tanggal"], 1, 1, 'C');
}

// Output PDF
$pdf->Output();
?>
