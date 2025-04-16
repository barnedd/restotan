<?php
// Mulai sesi
session_start();

// Panggil file koneksi database
include 'db_connection.php';

$error = ""; // Variabel untuk menampilkan pesan error

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data input
    $namauser = trim($_POST['namauser'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($namauser) || empty($password)) {
        $error = "Nama pengguna dan password harus diisi.";
    } else {
        // Query untuk mengecek namauser
        $sql = "SELECT * FROM user WHERE namauser = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $namauser);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah namauser ditemukan
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password = $user['password'] ?? '';

            // Pastikan password di database tidak kosong sebelum diverifikasi
            if (!empty($hashed_password) && password_verify($password, $hashed_password)) {
                // Login berhasil, simpan sesi
                $_SESSION['namauser'] = $user['namauser'];
                $_SESSION['role'] = $user['role'];

                // Redirect berdasarkan role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: dashboard_admin.php");
                        break;
                    case 'user':
                        header("Location: dashboard_user.php");
                        break;
                    default:
                        header("Location: dashboard_umum.php"); // Jika ada role lain
                        break;
                }
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Nama pengguna tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://img.freepik.com/premium-photo/restaurant-wood-table-background_577526-84.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .wrapper {
            width: 400px;
            padding: 40px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .wrapper h1 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #ffcc00;
        }
        .input-box {
            width: 100%;
            margin: 20px 0;
            position: relative;
        }
        .input-box input {
            width: 100%;
            padding: 12px 20px;
            background: transparent;
            border: 2px solid #ffcc00;
            border-radius: 40px;
            outline: none;
            color: #fff;
            font-size: 16px;
        }
        .input-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .input-box i {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: #ffcc00;
            cursor: pointer;
        }
        .btn {
            width: 100%;
            padding: 12px 20px;
            background: #ffcc00;
            color: black;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #e6b800;
        }
        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
        p {
            margin-top: 15px;
            font-size: 14px;
        }
        p a {
            color: #ffcc00;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" name="namauser" placeholder="Nama Pengguna" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock'></i>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Belum punya akun? <a href="register.php">Register</a></p>
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
