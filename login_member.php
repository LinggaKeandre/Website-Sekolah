<?php
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for member credentials
    $query = "SELECT * FROM members WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['member'] = $username;
        $_SESSION['member_id'] = $row['id']; // Set member_id in the session
        header("Location: member_absensi.php");
        exit();
    }

    $error = "Login gagal. Periksa kembali username dan password.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Member</title>
    <!-- Menggunakan Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background: #f5f5f5;
        }
        .login-container {
            margin-top: 100px;
            /* position: relative; <-- tidak dibutuhkan lagi, bisa dihapus */
        }
        .login-card {
            position: relative; /* Dijadikan relatif agar tombol absolute melekat di dalamnya */
            padding: 30px;
            border: none;
            border-radius: 10px;
            -webkit-box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.2);
            -moz-box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.2);
            box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.2);
        }
        .login-card .form-control {
            border-radius: 20px;
        }
        .login-card .btn {
            border-radius: 20px;
        }
        .back-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color:rgb(0, 0, 0);
            color: white;
            border-radius: 20px;
        }
        .toggle-password {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card">
                <!-- Tombol Kembali ditempatkan di dalam card -->
                <a href="index.php" class="btn back-btn">
                    <i class="bi bi-arrow-left-circle-fill" style="color: white;"></i>
                </a>
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Login Member</h3>
                    <?php if ($error != ''): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                        </div>
                        <div class="form-group position-relative">
                            <input type="password" name="password" class="form-control" placeholder="Password" required style="padding-right: 30px;">
                            <img src="assets/visible.png" class="toggle-password" id="togglePassword" 
                                 onclick="togglePasswordVisibility()" 
                                 style="width: 20px; height: 20px;">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login_admin.php" class="btn btn-secondary">Login as Admin</a>
                    </div>
                </div>
            </div>
            <p class="text-center mt-3">&copy; 2025 Sistem Absensi Siswa</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, dan jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.querySelector('input[name="password"]');
        const toggleIcon = document.getElementById('togglePassword');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.src = 'assets/hidden.png';
        } else {
            passwordInput.type = 'password';
            toggleIcon.src = 'assets/visible.png';
        }
    }
</script>
</body>
</html>
