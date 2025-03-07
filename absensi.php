<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Hapus semua data absensi jika sudah jam 15:01
$current_time = new DateTime();
$reset_time = new DateTime('15:01:00');
if ($current_time >= $reset_time && $current_time->format('H:i') == '15:01') {
    $conn->query("DELETE FROM absensi");
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
        // Hapus data akun dari alpha_accounts.php jika status adalah Alpha
        if ($status === 'Alpha') {
            $delete_account_stmt = $conn->prepare("DELETE FROM alpha_accounts WHERE siswa_id = ?");
            $delete_account_stmt->bind_param("i", $siswa_id);
            $delete_account_stmt->execute();
        }
        echo "<script>alert('Absensi berhasil ditambahkan.'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah absensi: " . $conn->error . "');</script>";
    }
}

// Proses update absensi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_absensi'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $foto = $_POST['old_foto']; // Gunakan foto lama jika tidak ada foto baru

    // Proses upload foto jika ada file baru
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

    // Jika status diubah menjadi Sakit atau Alpha, hapus foto
    if ($status === 'Sakit' || $status === 'Alpha') {
        $foto = '';
    }

    // Lakukan update ke database
    $stmt = $conn->prepare("UPDATE absensi SET status = ?, foto = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $foto, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Absensi berhasil diupdate.'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate absensi: " . $conn->error . "');</script>";
    }
}

// Proses hapus absensi
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM absensi WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Absensi berhasil dihapus.'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus absensi: " . $conn->error . "');</script>";
    }
}

// Proses hapus semua absensi
if (isset($_POST['delete_all'])) {
    $stmt = $conn->prepare("DELETE FROM absensi");
    if ($stmt->execute()) {
        echo "<script>alert('Semua data absensi berhasil dihapus.'); window.location.href='absensi.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus semua data absensi: " . $conn->error . "');</script>";
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
      <?php include 'sidebar.php'; ?>
      <!-- Konten Utama -->
      <div class="col-md-9">
        <h2 class="text-center">Absensi Siswa</h2>
        <div class="d-flex justify-content-between mb-3">
            <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua data absensi?');">
                <button type="submit" name="delete_all" class="btn btn-danger">Hapus Semua</button>
            </form>
            <form method="GET" class="form-inline">
                <a href="absensi.php" class="btn btn-secondary mr-2"><i class="fas fa-sync-alt"></i> Reset</a>
                <input type="text" name="search" class="form-control mr-2" placeholder="Cari Nama Siswa">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
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
                    <th>Aksi</th>
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
                    <td>
                        <!-- Tombol Edit memicu modal dengan data-id -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                        <a href="absensi.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Modal Edit Absensi (akan muncul di sisi kanan) -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Absensi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Konten formulir edit akan dimuat melalui AJAX -->
        </div>
      </div>
    </div>
  </div>
  
  <!-- jQuery, Popper.js, dan Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- AJAX untuk memuat formulir edit ke dalam modal -->
  <script>
    $(document).ready(function(){
      $('.edit-btn').click(function(){
        var id = $(this).data('id');
        $.ajax({
          url: 'edit_absensi_form.php',
          type: 'GET',
          data: { id: id },
          success: function(data){
            $('#editModal .modal-body').html(data);
            $('#editModal').modal('show');
          },
          error: function(){
            alert('Gagal memuat formulir edit.');
          }
        });
      });
    });
  </script>
</body>
</html>