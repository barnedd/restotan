<?php
include 'db_connection.php';

$nama = $_POST['nama_pelanggan'];
$alamat = $_POST['alamat'];
$telepon = $_POST['nomor_telepon'];
$menu = $_POST['menu'];
$jumlah = $_POST['jumlah'];

// 1. Simpan data pelanggan
$insert_pelanggan = mysqli_query($conn, "INSERT INTO pelanggan (namapelanggan, alamat, nohp) 
VALUES ('$nama', '$alamat', '$telepon')");

if (!$insert_pelanggan) {
    die("Gagal menyimpan data pelanggan: " . mysqli_error($conn));
}

$pelanggan_id = mysqli_insert_id($conn); // Ambil ID pelanggan terakhir

// 2. Hitung total & siapkan item
$total = 0;
$item_list = [];

for ($i = 0; $i < count($menu); $i++) {
    $idmenu = mysqli_real_escape_string($conn, $menu[$i]);
    $qty = (int)$jumlah[$i];
    if ($qty <= 0) continue;

    $result = mysqli_query($conn, "SELECT * FROM menu WHERE idmenu = '$idmenu'");
    $row = mysqli_fetch_assoc($result);

    $subtotal = $row['harga'] * $qty;
    $total += $subtotal;

    $item_list[] = [
        'idmenu' => $idmenu,
        'namamenu' => $row['namamenu'],
        'harga' => $row['harga'],
        'jumlah' => $qty,
        'subtotal' => $subtotal
    ];
}

// 3. Simpan ke tabel transaksi
$insert_transaksi = mysqli_query($conn, "INSERT INTO transaksi (tanggal, total) VALUES (NOW(), '$total')");
if (!$insert_transaksi) {
    die("Gagal menyimpan transaksi: " . mysqli_error($conn));
}

$penjualan_id = mysqli_insert_id($conn); // ID transaksi yang baru

// Pastikan ID transaksi valid
if (!$penjualan_id) {
    die("Terjadi kesalahan dalam mendapatkan ID transaksi.");
}

// 4. Simpan detail pesanan ke tabel detail_transaksi
foreach ($item_list as $item) {
    $insert_detail = mysqli_query($conn, "INSERT INTO detail_transaksi (idpesanan, idmenu, jumlah, subtotal) 
    VALUES ('$penjualan_id', '{$item['idmenu']}', '{$item['jumlah']}', '{$item['subtotal']}')");

    if (!$insert_detail) {
        die("Gagal menyimpan detail transaksi: " . mysqli_error($conn));
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
        .total { font-weight: bold; }
        .info { margin-top: 20px; }
    </style>
</head>
<body>

    <h2>Struk Pesanan</h2>
    <div class="info">
        <p><strong>Nama:</strong> <?= $nama ?></p>
        <p><strong>Alamat:</strong> <?= $alamat ?></p>
        <p><strong>Telepon:</strong> <?= $telepon ?></p>
        <p><strong>No. Transaksi:</strong> <?= $penjualan_id ?></p>
        <p><strong>Tanggal:</strong> <?= date('Y-m-d H:i:s') ?></p>
    </div>

    <h3>Detail Pesanan</h3>
    <table>
        <tr>
            <th>Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
        <?php foreach ($item_list as $item) { ?>
        <tr>
            <td><?= $item['namamenu'] ?></td>
            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
        </tr>
        <?php } ?>
        <tr class="total">
            <td colspan="3" align="right">Total</td>
            <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
        </tr>
    </table>

    <script>
        window.print();
    </script>

</body>
</html>
