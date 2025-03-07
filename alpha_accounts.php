<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Ambil data akun dengan status Alpha
$alpha_result = $conn->query("
    SELECT siswa.id, 
           siswa.nama, 
           siswa.nomor_absen, 
           siswa.kelas, 
           siswa.jurusan
    FROM siswa
    LEFT JOIN absensi ON siswa.id = absensi.siswa_id
    WHERE absensi.siswa_id IS NULL
    ORDER BY siswa.id ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Akun dengan Status Alpha</title>
    <!-- Bootstrap CSS -->
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
        <h2 class="text-center">Akun dengan Status Alpha</h2>
        <!-- Tabel Data Akun dengan Status Alpha -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nomor Absen</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $alpha_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['nomor_absen'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jurusan'] ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- jQuery versi penuh, Popper.js, dan Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
