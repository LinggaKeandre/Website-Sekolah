<?php
include 'config.php';
if (!isset($_SESSION['member'])) {
    header("Location: login_member.php");
    exit();
}

$error = '';
$warning = '';

// Set timezone to Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Cek waktu absensi
$current_time = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$cutoff_time = new DateTime('07:00:00', new DateTimeZone('Asia/Jakarta'));
$is_late = $current_time > $cutoff_time;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_absensi'])) {
    $status = $_POST['status'];
    $username = $_SESSION['member']; // Ambil username dari sesi login

    // Ambil siswa_id berdasarkan username
    $result = $conn->query("SELECT id, nomor_absen FROM siswa WHERE nama = '$username'");
    $siswa = $result->fetch_assoc();
    $siswa_id = $siswa['id'];
    $nomor_absen = $siswa['nomor_absen'];

    // Validasi dasar
    if ($siswa_id == '' || $nomor_absen == '') {
        $error = "Data siswa tidak valid.";
    }

    // Jika status Hadir/Terlambat, foto wajib diupload
    if (($status === 'Hadir' || $status === 'Terlambat') && $_FILES['foto']['error'] == 4) {
        $error = "Foto wajib diupload jika status Hadir atau Terlambat.";
    }

    // Jika tidak ada error, proses upload foto (jika diperlukan)
    if ($error == '') {
        $foto = "";
        if ($status === 'Hadir' || $status === 'Terlambat') {
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
                $new_filename = $target_dir . uniqid() . "." . $file_extension;
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $new_filename)) {
                    $foto = $new_filename;
                } else {
                    $error = "Gagal mengupload foto.";
                }
            }
        } else {
            // Untuk status Sakit, foto tidak wajib
            $foto = "";
        }
    }

    if ($error == '') {
        $sql = "INSERT INTO absensi (siswa_id, status, foto, nomor_absen, tanggal) VALUES ('$siswa_id', '$status', '$foto', '$nomor_absen', NOW())";
        if ($conn->query($sql)) {
            header("Location: member_absensi.php");
            exit();
        } else {
            $error = "Gagal menambahkan absensi: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Absensi</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
      #previewFoto {
        max-width: 200px;
        margin-top: 10px;
        display: none;
      }
    </style>
</head>
<body>
  <?php include 'includes/clock.php'; ?>
  <div class="container-fluid mt-3">
    <div class="row">
      <!-- Sidebar -->
      <?php include 'member_sidebar.php'; ?>

      <!-- Konten Utama -->
      <div class="col-md-9">
          <h2 class="text-center">Tambah Absensi</h2>
          <?php if ($error != ''): ?>
              <div class="alert alert-danger"><?= $error; ?></div>
          <?php endif; ?>
          <?php if ($is_late): ?>
              <div class="alert alert-warning">Anda telah melewati jam 7 pagi, status akan otomatis diubah menjadi Terlambat.</div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">
              <!-- Pilih Status -->
              <div class="form-group">
                  <label for="status">Status:</label>
                  <select name="status" id="status" class="form-control" required>
                      <?php if ($is_late): ?>
                          <option value="Terlambat">Terlambat</option>
                          <option value="Sakit">Sakit</option>
                      <?php else: ?>
                          <option value="Hadir">Hadir</option>
                          <option value="Sakit">Sakit</option>
                          <option value="Terlambat">Terlambat</option>
                      <?php endif; ?>
                  </select>
              </div>

              <!-- Upload Foto (opsional) -->
              <div class="form-group" id="fotoContainer">
                  <label for="foto">Upload Foto:</label>
                  <input type="file" name="foto" class="form-control-file" id="foto" accept="image/*">
                  <img id="previewFoto" src="#" alt="Preview Foto">
              </div>

              <!-- Tombol Aksi -->
              <button type="submit" name="add_absensi" class="btn btn-primary">Tambah Absensi</button>
              <a href="member_absensi.php" class="btn btn-secondary ml-2">Kembali</a>
          </form>
      </div>
    </div>
  </div>
  
  <!-- JavaScript untuk preview foto -->
  <script>
    document.getElementById("foto").onchange = function(event) {
      var output = document.getElementById("previewFoto");
      if(event.target.files && event.target.files[0]) {
          output.src = URL.createObjectURL(event.target.files[0]);
          output.style.display = "block";
      } else {
          output.style.display = "none";
      }
    };

    // Sembunyikan field foto jika status Sakit
    document.getElementById("status").addEventListener('change', function() {
      var status = this.value;
      var fotoContainer = document.getElementById("fotoContainer");
      if (status === "Sakit") {
          fotoContainer.style.display = "none";
      } else {
          fotoContainer.style.display = "block";
      }
    });
  </script>

  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
