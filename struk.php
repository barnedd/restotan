<?php
include 'db_connection.php';

if (!isset($_GET['id'])) {
    echo "ID penjualan tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

// Ambil data penjualan
$penjualan = mysqli_query($conn, "SELECT * FROM penjualan WHERE PenjualanID = '$id'");
$data_penjualan = mysqli_fetch_assoc($penjualan);

// Ambil data pelanggan
$pelanggan = mysqli_query($conn, "
    SELECT p.*
    FROM pelanggan p
    JOIN penjualan pj ON p.PelangganID = pj.PenjualanID
    WHERE pj.PenjualanID = '$id'
    LIMIT 1
");
$data_pelanggan = mysqli_fetch_assoc($pelanggan);

// Ambil detail pesanan
$detail = mysqli_query($conn, "
    SELECT d.*, m.namamenu, m.harga 
    FROM detailpenjualan d
    JOIN menu m ON d.ProdukID = m.idmenu
    WHERE d.PenjualanID = '$id'
");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk #<?= $id ?></title>
    <style>
        body { font-family: monospace; width: 300px; margin: auto; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 6px; text-align: left; }
        .total { font-weight: bold; border-top: 1px solid #000; }
        .center { text-align: center; }
        .info { font-size: 13px; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Warung Barnett</h2>
<p class="center">Jl. Contoh Alamat No. 123<br>Telp: 0812-XXXX-XXXX</p>
<hr>

<div class="info">
    <p><strong>No. Transaksi:</strong> <?= $id ?></p>
    <p><strong>Nama:</strong> <?= $data_pelanggan['NamaPelanggan'] ?? '-' ?></p>
    <p><strong>Alamat:</strong> <?= $data_pelanggan['Alamat'] ?? '-' ?></p>
    <p><strong>Telepon:</strong> <?= $data_pelanggan['NomorTelepon'] ?? '-' ?></p>
    <p><strong>Tanggal:</strong> <?= date('d-m-Y H:i:s', strtotime($data_penjualan['Tanggal'])) ?></p>
</div>

<h3>Detail Pesanan</h3>
<table>
    <tr>
        <th>Menu</th>
        <th>Qty</th>
        <th>Harga</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($detail)) { ?>
        <tr>
            <td><?= $row['namamenu'] ?></td>
            <td><?= $row['JumlahProduk'] ?></td>
            <td>Rp<?= number_format($row['Subtotal'], 0, ',', '.') ?></td>
        </tr>
    <?php } ?>
    <tr class="total">
        <td colspan="2">TOTAL</td>
        <td>Rp<?= number_format($data_penjualan['Total'], 0, ',', '.') ?></td>
    </tr>
</table>

<p class="center">Terima kasih atas pesanan Anda!</p>

<script>
    window.print(); // Otomatis cetak
</script>

</body>
</html>
