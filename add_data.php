<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Proses tambah siswa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama         = $_POST['nama'];
    $kelas        = $_POST['kelas'];
    $jurusan      = $_POST['jurusan'];
    $nomor_absen  = $_POST['nomor_absen'];
    $password     = $_POST['password']; // Simpan password tanpa hashing

    // Insert ke tabel siswa
    $sql_siswa = "INSERT INTO siswa (nama, kelas, jurusan, nomor_absen) VALUES ('$nama', '$kelas', '$jurusan', '$nomor_absen')";
    $conn->query($sql_siswa);

    // Cek jika username sudah ada di tabel members
    $sql_check = "SELECT * FROM members WHERE username='$nama'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        // Jika duplikat, tampilkan pesan error dan hentikan proses
        echo "<div class='alert alert-danger text-center'>Error: Username '$nama' sudah terdaftar. Silakan gunakan username lain.</div>";
        exit();
    }

    // Jika tidak duplikat, lakukan INSERT ke tabel members
    $sql_member = "INSERT INTO members (username, password, role) VALUES ('$nama', '$password', 'member')";
    $conn->query($sql_member);

    // Redirect kembali ke halaman data siswa
    header("Location: siswa.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
  <?php include 'includes/clock.php'; ?>
  <div class="container-fluid mt-3">
    <div class="row">
      <!-- Sidebar -->
      <?php include 'sidebar.php'; ?>

      <!-- Konten Utama -->
      <div class="col-md-9">
        <h2 class="text-center mb-4">Tambah Siswa</h2>
        <div class="card">
          <div class="card-body">
            <form method="POST" class="mb-4">
              <div class="form-row">
                <!-- Nama -->
                <div class="form-group col-md-3">
                  <label for="nama">Nama Lengkap</label>
                  <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                
                <!-- Kelas -->
                <div class="form-group col-md-3">
                  <label for="kelas">Kelas</label>
                  <select name="kelas" id="kelas" class="form-control" required>
                    <option value="">-- Pilih Kelas --</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
                
                <!-- Jurusan -->
                <div class="form-group col-md-3">
                  <label for="jurusan">Jurusan</label>
                  <select name="jurusan" id="jurusan" class="form-control" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <option value="RPL">RPL</option>
                    <option value="DKV1">DKV1</option>
                    <option value="DKV2">DKV2</option>
                    <option value="BR">BR</option>
                    <option value="MP">MP</option>
                    <option value="AKL">AKL</option>
                  </select>
                </div>
                
                <!-- Nomor Absen -->
                <div class="form-group col-md-3">
                  <label for="nomor_absen">Nomor Absen</label>
                  <input type="number" name="nomor_absen" id="nomor_absen" class="form-control" min="1" max="42" required>
                </div>
              </div>
              <div class="form-row">
                <!-- Password -->
                <div class="form-group col-md-6">
                  <label for="password">Password</label>
                  <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required style="padding-right: 30px;">
                    <img src="assets/visible.png" class="toggle-password" id="togglePassword" onclick="togglePasswordVisibility()" style="width: 20px; height: 20px; position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Tambah Siswa</button>
              <a href="siswa.php" class="btn btn-secondary ml-2">Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
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
