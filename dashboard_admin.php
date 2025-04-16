<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

include 'db_connection.php';

// Ambil jumlah meja
$result_meja = $conn->query("SELECT COUNT(*) AS total_meja FROM meja");
$row_meja = $result_meja->fetch_assoc();
$total_meja = $row_meja['total_meja'];

// Ambil jumlah pesanan
$result_pesanan = $conn->query("SELECT COUNT(*) AS total_pesanan FROM pesanan");
$row_pesanan = $result_pesanan->fetch_assoc();
$total_pesanan = $row_pesanan['total_pesanan'];

// Ambil jumlah menu
$result_menu = $conn->query("SELECT COUNT(*) AS total_menu FROM menu");
$row_menu = $result_menu->fetch_assoc();
$total_menu = $row_menu['total_menu'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        .sidebar ul li a i { margin-right: 10px; font-size: 18px; }
        .content { flex-grow: 1; padding: 20px; margin-left: 250px; transition: margin-left 0.3s ease-in-out; }
        .dashboard-container { display: flex; gap: 20px; flex-wrap: wrap; }
        .dashboard-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 220px; text-align: center; display: flex; flex-direction: column; align-items: center; }
        .dashboard-card i { font-size: 40px; margin-bottom: 10px; }
        .dashboard-card h3 { margin-bottom: 10px; font-size: 18px; }
        .dashboard-card p { font-size: 24px; font-weight: bold; }
        .card-meja { color: #3498db; }
        .card-pesanan { color: #e74c3c; }
        .card-menu { color: #2ecc71; }
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
        <h1>Dashboard Admin</h1>
        <div class="dashboard-container">
            <div class="dashboard-card">
                <i class='bx bx-table card-meja'></i>
                <h3>Total Meja</h3>
                <p><?php echo $total_meja; ?></p>
            </div>
            <div class="dashboard-card">
                <i class='bx bx-receipt card-pesanan'></i>
                <h3>Total Pesanan</h3>
                <p><?php echo $total_pesanan; ?></p>
            </div>
            <div class="dashboard-card">
                <i class='bx bx-food-menu card-menu'></i>
                <h3>Total Menu</h3>
                <p><?php echo $total_menu; ?></p>
            </div>
        </div>
    </div>
</body>
</html>
