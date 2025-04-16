<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

include 'db_connection.php';

// Ambil notifikasi dari session jika ada
$notif = "";
$notifClass = "";
if (isset($_SESSION['notif'])) {
    $notif = $_SESSION['notif'];
    $notifClass = $_SESSION['notifClass'];
    unset($_SESSION['notif'], $_SESSION['notifClass']);
}

// Tambah menu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_produk'])) {
    $namamenu = isset($_POST['nama_produk']) ? $_POST['nama_produk'] : '';
    $harga = isset($_POST['harga']) ? $_POST['harga'] : 0;
    $stok = isset($_POST['stok']) ? $_POST['stok'] : 0;
    $kategori = isset($_POST['kategori']) ? $_POST['kategori'] : '';

    if (!empty($namamenu) && !empty($kategori)) {
        $conn->query("INSERT INTO menu (namamenu, harga, stok, kategori) VALUES ('$namamenu', '$harga', '$stok', '$kategori')");
        $_SESSION['notif'] = "Menu berhasil ditambahkan.";
        $_SESSION['notifClass'] = "success";
        header("Location: entri_produk.php");
        exit();
    } else {
        $_SESSION['notif'] = "Harap isi semua field.";
        $_SESSION['notifClass'] = "error";
        header("Location: entri_produk.php");
        exit();
    }
}

// Hapus menu
if (isset($_GET['hapus'])) {
    $id_menu = $_GET['hapus'];
    $conn->query("DELETE FROM menu WHERE idmenu = '$id_menu'");
    $_SESSION['notif'] = "Menu berhasil dihapus.";
    $_SESSION['notifClass'] = "error";
    header("Location: entri_produk.php");
    exit();
}

// Ambil data menu untuk ditampilkan di tabel
$result = $conn->query("SELECT * FROM menu");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entri Produk</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background: #f4f4f4; overflow: hidden; }
        .sidebar { width: 250px; background: #222; color: white; padding: 20px; position: fixed; height: 100%; }
        .sidebar h2 { text-align: center; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { color: white; text-decoration: none; display: flex; align-items: center; padding: 10px; border-radius: 5px; }
        .sidebar ul li a:hover { background: #444; }
        .sidebar ul li a i { margin-right: 10px; }
        .content { flex-grow: 1; padding: 20px; margin-left: 250px; transition: margin-left 0.3s ease-in-out; }
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #ffcc00; color: black; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-delete { background: red; color: white; }
        .btn-add { background: green; color: white; margin-top: 10px; display: inline-block; }
        .notif-popup {
            display: <?php echo $notif ? 'block' : 'none'; ?>;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            opacity: 0;
            animation: fadeInOut 3s ease-in-out;
        }
        .success { background: #28a745; }
        .error { background: #dc3545; }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
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
        <h1>Entri Menu</h1>
        <?php if ($notif): ?>
            <div class="notif-popup <?php echo $notifClass; ?>" id="notifPopup">
                <?php echo $notif; ?>
            </div>
        <?php endif; ?>
        <div class="table-container">
            <h2>Tambah Menu</h2>
            <form method="POST">
                <input type="text" name="nama_produk" placeholder="Nama Menu" required>
                <input type="number" name="harga" placeholder="Harga" required>
                <input type="number" name="stok" placeholder="Stok" required>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                </select>
                <button type="submit" name="tambah_produk" class="btn btn-add">Tambah Menu</button>
            </form>
            <table>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['namamenu']; ?></td>
                        <td><?php echo $row['harga']; ?></td>
                        <td><?php echo $row['Stok']; ?></td>
                        <td><?php echo ucfirst($row['Kategori']); ?></td>
                        <td>
                            <a href="?hapus=<?php echo $row['idmenu']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
