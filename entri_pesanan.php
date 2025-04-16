<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

include 'db_connection.php';

// Ambil data menu dari tabel menu
$query = "SELECT idmenu, namamenu, harga FROM menu";
$result = mysqli_query($conn, $query);

// Cek jika query gagal
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entri Pesanan</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background: #f4f4f4; }
        .sidebar { width: 250px; background: #222; color: white; padding: 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 20px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { color: white; text-decoration: none; display: flex; align-items: center; padding: 10px; border-radius: 5px; }
        .sidebar ul li a:hover { background: #444; }
        .sidebar ul li a i { margin-right: 10px; }
        .content { flex-grow: 1; padding: 20px; }
        .container { max-width: 800px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .order-items { margin-top: 20px; }
        .order-item { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .order-item select, .order-item input { flex: 1; }
        .order-item .harga { width: 100px; text-align: right; font-weight: bold; }
        .order-item button { background: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
        .order-item button:hover { background: darkred; }
        .add-item-btn, .submit-btn { display: block; width: 100%; padding: 10px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        .add-item-btn { background: #28a745; color: white; }
        .add-item-btn:hover { background: #218838; }
        .submit-btn { background: #007bff; color: white; }
        .submit-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard_admin.php"><i class='bx bxs-dashboard'></i> Dashboard</a></li>
            <li><a href="entri_meja.php"><i class='bx bx-table'></i> Entri Meja</a></li>
            <li><a href="entri_pesanan.php"><i class='bx bx-receipt'></i> Pesanan</a></li>
            <li><a href="entri_produk.php"><i class='bx bx-food-menu'></i> Menu</a></li>
            <li><a href="login.php"><i class='bx bx-log-out'></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="container">
            <h2>Entri Pesanan</h2>
            <form action="proses_pesanan.php" method="POST">
                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" required>
                </div>
                <div class="form-group">
                    <label for="nomor_telepon">Nomor Telepon</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" required>
                </div>

                <div class="order-items">
                    <h3>Pesanan</h3>
                    <div class="order-item">
                        <select name="menu[]" class="menu-select" required>
                            <option value="">Pilih Menu</option>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <option value="<?= $row['idmenu'] ?>" data-harga="<?= $row['harga'] ?>">
                                    <?= $row['namamenu'] ?> - Rp<?= number_format($row['harga'], 0, ',', '.') ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <input type="number" name="jumlah[]" class="jumlah" placeholder="Jumlah" min="1" value="1" required>
                        <span class="harga">Rp0</span>
                        <button type="button" class="hapus">X</button>
                    </div>
                </div>
                <button type="button" class="add-item-btn">Tambah Item</button>
                <button type="submit" class="submit-btn">Cetak Struk & Simpan Pesanan</button>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelector(".add-item-btn").addEventListener("click", function () {
                let orderItems = document.querySelector(".order-items");
                let newItem = document.querySelector(".order-item").cloneNode(true);

                newItem.querySelector(".jumlah").value = "1";
                newItem.querySelector(".harga").textContent = "Rp0";
                newItem.querySelector(".menu-select").selectedIndex = 0;

                orderItems.appendChild(newItem);
            });

            document.addEventListener("input", function (event) {
                if (event.target.classList.contains("menu-select") || event.target.classList.contains("jumlah")) {
                    let parent = event.target.closest(".order-item");
                    let harga = parent.querySelector(".menu-select").selectedOptions[0].getAttribute("data-harga") || 0;
                    let jumlah = parent.querySelector(".jumlah").value || 1;
                    let total = parseInt(harga) * parseInt(jumlah);
                    
                    parent.querySelector(".harga").textContent = "Rp" + total.toLocaleString();
                }
            });

            document.addEventListener("click", function (event) {
                if (event.target.classList.contains("hapus")) {
                    if (document.querySelectorAll(".order-item").length > 1) {
                        event.target.closest(".order-item").remove();
                    }
                }
            });
        });
    </script>
</body>
</html>
