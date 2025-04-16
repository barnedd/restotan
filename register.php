<?php
session_start();
include 'db_connection.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namauser = $_POST['namauser'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (empty($namauser) || empty($password) || empty($role)) {
        $error = "Semua field harus diisi.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (namauser, password, role) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $namauser, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftarkan pengguna.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .wrapper h1 {
            font-size: 26px;
            color: #ffcc00;
            margin-bottom: 20px;
        }
        .input-box {
            width: 100%;
            margin: 15px 0;
            position: relative;
        }
        .input-box input, .input-box select {
            width: 100%;
            padding: 12px 20px;
            background: transparent;
            border: 2px solid #ffcc00;
            border-radius: 40px;
            outline: none;
            color: #fff;
            font-size: 16px;
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
        }
        .btn:hover {
            background: #e6b800;
        }
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            width: 300px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .modal.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
        .modal .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .modal.success .icon {
            color: green;
        }
        .modal.error .icon {
            color: red;
        }
        .modal p {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .modal .modal-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .modal .close-btn {
            background: gray;
            color: white;
        }
        .modal .login-btn {
            background: #ffcc00;
            color: black;
        }
        .modal .close-btn:hover {
            background: darkgray;
        }
        .modal .login-btn:hover {
            background: #e6b800;
        }
        /* Style untuk link login */
        .login-link {
            color: #ffcc00;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Register</h1>
            <div class="input-box">
                <input type="text" name="namauser" placeholder="Nama Pengguna" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-box">
                <select name="role" required>
                    <option value="">-- Pilih Role --</option>
                    <?php
                    $query = "SHOW COLUMNS FROM user LIKE 'role'";
                    $result = $conn->query($query);
                    if ($row = $result->fetch_assoc()) {
                        $enum_list = str_replace(["enum('", "')"], '', $row['Type']);
                        $roles = explode("','", $enum_list);
                        foreach ($roles as $role) {
                            echo "<option value='$role'>$role</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn">Register</button>
            <p>Sudah punya akun? <a href="login.php" class="login-link">Login</a></p>
        </form>
    </div>

    <!-- Modal Sukses -->
    <div id="successModal" class="modal success">
        <div class="icon">✅</div>
        <p><?php echo $success; ?></p>
        <button class="modal-btn login-btn" onclick="window.location.href='login.php'">Ke Login</button>
    </div>

    <!-- Modal Error -->
    <div id="errorModal" class="modal error">
        <div class="icon">❌</div>
        <p><?php echo $error; ?></p>
        <button class="modal-btn close-btn" onclick="closeModal('errorModal')">Tutup</button>
    </div>

    <script>
        function showModal(id) {
            document.getElementById(id).classList.add('show');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('show');
        }

        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($success)) { ?>
                showModal('successModal');
            <?php } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)) { ?>
                showModal('errorModal');
            <?php } ?>
        });
    </script>
</body>
</html>
