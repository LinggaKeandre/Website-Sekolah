<?php
include 'config.php';
if (!isset($_SESSION['member'])) {
    header("Location: login_member.php");
    exit();
}

// Proses tambah absensi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_absensi'])) {
    $siswa_id = $_POST['siswa_id'];
    $status = $_POST['status'];
    
    // Proses upload foto jika ada file baru
    $foto = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION);
        $new_filename = $target_dir . uniqid() . "." . $file_extension;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $new_filename)) {
            $foto = $new_filename;
        }
    }

    // Lakukan insert ke database
    $stmt = $conn->prepare("INSERT INTO absensi (siswa_id, status, foto, tanggal) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $siswa_id, $status, $foto);
    if ($stmt->execute()) {
        echo "<script>alert('Absensi berhasil ditambahkan.'); window.location.href='member_absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah absensi: " . $conn->error . "');</script>";
    }
}

// Ambil data absensi beserta informasi siswa
$search_query = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $search_query = "AND siswa.nama LIKE '%$search%'";
}

$absensi_result = $conn->query("
    SELECT absensi.id, 
           siswa.nama, 
           siswa.nomor_absen, 
           siswa.kelas, 
           siswa.jurusan, 
           absensi.status, 
           absensi.tanggal, 
           absensi.foto
    FROM absensi
    JOIN siswa ON absensi.siswa_id = siswa.id
    WHERE 1=1 $search_query
    ORDER BY absensi.id ASC
");
?>         
<!DOCTYPE html>
<html>
<head>
    <title>Absensi Siswa</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
  <?php include 'includes/clock.php'; ?>
  <div class="container-fluid mt-3">
    <div class="row">
      <!-- Sidebar -->
      <?php include 'member_sidebar.php'; ?>
      <!-- Konten Utama -->
      <div class="col-md-9">
        <h2 class="text-center">Absensi Siswa</h2>
        <div class="mb-3">
            <a href="member_add_absensi.php" class="btn btn-success">Tambah Absensi</a>
        </div>
        <!-- Tabel Data Absensi -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nomor Absen</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $absensi_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['nomor_absen'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jurusan'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td>
                        <?php if (!empty($row['foto'])): ?>
                            <img src="<?= $row['foto'] ?>" style="max-width:100px;">
                        <?php endif; ?>
                    </td>
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