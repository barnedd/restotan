<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

include 'db_connection.php';

// Fungsi untuk membuat MejaID acak
function generateMejaID($conn) {
    do {
        $randomID = 'M' . rand(1000, 9999);
        $result = $conn->query("SELECT * FROM meja WHERE MejaID = '$randomID'");
    } while ($result->num_rows > 0);
    return $randomID;
}

// Notifikasi
$notif = "";

// Tambah meja
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_meja'])) {
    $nomor_meja = $_POST['nomor_meja'];
    $kapasitas = $_POST['kapasitas'];
    $mejaID = generateMejaID($conn);
    $conn->query("INSERT INTO meja (MejaID, nomor_meja, Kapasitas) VALUES ('$mejaID', '$nomor_meja', '$kapasitas')");
    $notif = "Meja berhasil ditambahkan.";
}

// Hapus meja
if (isset($_GET['hapus'])) {
    $id_meja = $_GET['hapus'];
    $conn->query("DELETE FROM meja WHERE MejaID = '$id_meja'");
    $notif = "Meja berhasil dihapus.";
}

// Ambil data meja untuk ditampilkan di tabel
$result = $conn->query("SELECT nomor_meja, Kapasitas, MejaID FROM meja");

// Cek apakah user ingin mengedit meja tertentu
$edit_mode = false;
$edit_data = null;

if (isset($_GET['edit'])) {
    $id_meja = $_GET['edit'];
    $query = $conn->query("SELECT * FROM meja WHERE MejaID = '$id_meja'");
    if ($query->num_rows > 0) {
        $edit_data = $query->fetch_assoc();
        $edit_mode = true;
    }
}

// Update meja jika form edit dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_meja'])) {
    $id_meja = $_POST['id_meja'];
    $nomor_meja = $_POST['nomor_meja'];
    $kapasitas = $_POST['kapasitas'];
    $conn->query("UPDATE meja SET nomor_meja = '$nomor_meja', Kapasitas = '$kapasitas' WHERE MejaID = '$id_meja'");
    $notif = "Meja berhasil diperbarui.";
    header("Location: entri_meja.php"); // Hindari resubmission form
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entri Meja</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; height: 100vh; background: #f4f4f4; }
        .sidebar { width: 250px; background: #222; color: white; padding: 20px; transition: 0.3s; }
        .sidebar h2 { text-align: center; margin-bottom: 20px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { color: white; text-decoration: none; display: flex; align-items: center; padding: 10px; border-radius: 5px; }
        .sidebar ul li a:hover { background: #444; }
        .sidebar ul li a i { margin-right: 10px; }
        .content { flex-grow: 1; padding: 20px; }
        .table-container { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #ffcc00; color: black; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-delete { background: red; color: white; }
        .btn-edit { background: blue; color: white; }
        .btn-add { background: green; color: white; margin-top: 10px; display: inline-block; }
        .notif-popup { 
            display: <?php echo $notif ? 'block' : 'none'; ?>; 
            position: fixed; 
            top: 20px; 
            left: 50%; 
            transform: translateX(-50%); 
            background: #28a745; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 5px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            z-index: 1000;
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
            <li><a href="logout.php"><i class='bx bx-log-out'></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h1>Entri Meja</h1>
        <?php if ($notif): ?>
            <div class="notif-popup" id="notifPopup">
                <?php echo $notif; ?>
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('notifPopup').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>

        <div class="table-container">
            <?php if ($edit_mode): ?>
                <h2>Edit Meja</h2>
                <form method="POST">
                    <input type="hidden" name="id_meja" value="<?php echo $edit_data['MejaID']; ?>">
                    <input type="text" name="nomor_meja" value="<?php echo $edit_data['nomor_meja']; ?>" required>
                    <input type="number" name="kapasitas" value="<?php echo $edit_data['Kapasitas']; ?>" required>
                    <button type="submit" name="edit_meja" class="btn btn-edit">Perbarui</button>
                </form>
            <?php else: ?>
                <h2>Tambah Meja</h2>
                <form method="POST">
                    <input type="text" name="nomor_meja" placeholder="Nomor Meja" required>
                    <input type="number" name="kapasitas" placeholder="Kapasitas" required>
                    <button type="submit" name="tambah_meja" class="btn btn-add">Tambah Meja</button>
                </form>
            <?php endif; ?>
            
            <table>
                <tr>
                    <th>Nomor Meja</th>
                    <th>Kapasitas</th>
                    <th>Aksi</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['nomor_meja']; ?></td>
                        <td><?php echo $row['Kapasitas']; ?></td>
                        <td>
                            <a href="?edit=<?php echo $row['MejaID']; ?>" class="btn btn-edit">Edit</a>
                            <a href="?hapus=<?php echo $row['MejaID']; ?>" class="btn btn-delete">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
