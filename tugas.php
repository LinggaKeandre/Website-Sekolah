<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Proses hapus tugas
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // Hapus tugas yang sudah dikumpulkan terkait dengan tugas ini
    $stmt = $conn->prepare("DELETE FROM submitted_tugas WHERE tugas_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Hapus tugas
    $stmt = $conn->prepare("DELETE FROM tugas WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Tugas berhasil dihapus.'); window.location.href='tugas.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus tugas: " . $conn->error . "');</script>";
    }
}

// Ambil data tugas
$tugas_result = $conn->query("SELECT * FROM tugas ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Tugas</title>
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
        <h2 class="text-center">Data Tugas</h2>
        
        <!-- Tombol Tambah Tugas -->
        <div class="mb-4 d-flex justify-content-between">
            <a href="add_tugas.php" class="btn btn-primary">Tambah Tugas</a>
            <a href="submitted_tugas.php" class="btn btn-primary">Lihat Tugas yang Dikumpulkan</a>
        </div>

        <!-- Tabel Data Tugas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul Tugas</th>
                    <th>Deskripsi</th>
                    <th>Mapel</th> <!-- Kolom baru -->
                    <th>Deadline</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $tugas_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= wordwrap($row['judul'], 14, "<br>\n", true) ?></td>
                    <td><?= wordwrap($row['deskripsi'], 14, "<br>\n", true) ?></td>
                    <td><?= $row['mapel'] ?></td> <!-- Data mapel -->
                    <td><?= $row['deadline'] ?></td>
                    <td>
                        <?php 
                        $files = json_decode($row['files'], true);
                        if (!empty($files)): 
                            if (count($files) == 1): ?>
                                <a href="<?= $files[0] ?>" target="_blank">Download</a>
                            <?php else: ?>
                                <a href="download_zip.php?tugas_id=<?= $row['id'] ?>&judul=<?= urlencode($row['judul']) ?>" target="_blank">Download ZIP</a>
                            <?php endif; 
                        endif; ?>
                    </td>
                    <td>
                        <a href="tugas.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
