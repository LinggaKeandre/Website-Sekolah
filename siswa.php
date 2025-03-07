<?php
// siswa.php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Hapus Siswa
if (isset($_GET['delete_siswa'])) {
  $id = $_GET['delete_siswa'];
  
  // Ambil nama siswa sebelum menghapus data siswa
  $stmt_select = $conn->prepare("SELECT nama FROM siswa WHERE id=?");
  $stmt_select->bind_param("i", $id);
  $stmt_select->execute();
  $result = $stmt_select->get_result();
  
  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $nama = $row['nama'];
      
      // Hapus data siswa
      $stmt = $conn->prepare("DELETE FROM siswa WHERE id=?");
      $stmt->bind_param("i", $id);
      if ($stmt->execute()) {
          // Hapus data dari tabel members berdasarkan username yang sudah diambil
          $stmt_member = $conn->prepare("DELETE FROM members WHERE username = ?");
          $stmt_member->bind_param("s", $nama);
          if (!$stmt_member->execute()) {
              $_SESSION['error'] = "Gagal menghapus data dari tabel members: " . $stmt_member->error;
              header("Location: siswa.php");
              exit();
          }
          $_SESSION['success'] = "Data siswa berhasil dihapus!";
      } else {
          $_SESSION['error'] = "Gagal menghapus data siswa: " . $stmt->error;
      }
  } else {
      $_SESSION['error'] = "Data siswa tidak ditemukan!";
  }
  header("Location: siswa.php");
  exit();
}



// Update Siswa (proses update form submit dari modal)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_siswa'])) {
    $id          = $_POST['id'];
    $nama        = $_POST['nama'];
    $kelas       = $_POST['kelas'];
    $jurusan     = $_POST['jurusan'];
    $nomor_absen = $_POST['nomor_absen'];

    $stmt = $conn->prepare("UPDATE siswa SET nama=?, kelas=?, jurusan=?, nomor_absen=? WHERE id=?");
    $stmt->bind_param("ssssi", $nama, $kelas, $jurusan, $nomor_absen, $id);
    $stmt->execute();
    // Setelah update, redirect agar modal tidak muncul kembali
    header("Location: siswa.php");
    exit();
}

// Ambil data siswa
$search_query = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $search_query = "WHERE nama LIKE '%$search%'";
}

$siswa_result = $conn->query("SELECT * FROM siswa $search_query ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Siswa</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
      /* Custom CSS untuk modal agar muncul di sisi kanan */
      .modal-dialog-right {
          position: fixed;
          right: 0;
          margin: 0;
          width: 400px;
          height: 100%;
      }
      .modal-content {
          height: 100%;
          border: none;
          border-radius: 0;
      }
    </style>
</head>
<body>
  <?php include 'includes/clock.php'; ?>
  <div class="container-fluid mt-3">
    <div class="row">
      <!-- Sidebar -->
      <?php include 'sidebar.php'; ?>

      <!-- Konten Utama -->
        <div class="col-md-9">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <h2 class="text-center">Data Siswa</h2>

        
        <!-- Tombol Tambah Siswa -->
        <div class="d-flex justify-content-between mb-4">
            <a href="add_data.php" class="btn btn-primary">Tambah Siswa</a>
            <form method="GET" class="form-inline">
                <a href="siswa.php" class="btn btn-secondary mr-2"><i class="fas fa-sync-alt"></i> Refresh</a>
                <input type="text" name="search" class="form-control mr-2" placeholder="Cari Nama Siswa">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <!-- Tabel Data Siswa -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>Nomor Absen</th>
                    <th>Keterangan</th> <!-- Kolom baru -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $siswa_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jurusan'] ?></td>
                    <td><?= $row['nomor_absen'] ?></td>
                    
                    <!-- Keterangan: Cek apakah siswa ini sudah pernah diabsen -->
                    <td>
                      <?php
                        // Cek di tabel absensi
                        $siswa_id = $row['id'];
                        $sqlCheck = "SELECT COUNT(*) AS total FROM absensi WHERE siswa_id = $siswa_id";
                        $resultCheck = $conn->query($sqlCheck);
                        $count = 0;
                        if ($resultCheck) {
                            $checkRow = $resultCheck->fetch_assoc();
                            $count = $checkRow['total'];
                        }

                        if ($count > 0) {
                            // Tampilkan kotak dengan checklist
                            echo '<i class="bi bi-check-square-fill" style="font-size: 1.5rem; color: green;"></i>';
                        } else {
                            // Tampilkan kotak kosong dan tambahkan ke halaman alpha_accounts.php jika belum ada
                            echo '<i class="bi bi-square" style="font-size: 1.5rem; color: gray;"></i>';
                            $alpha_check = $conn->query("SELECT COUNT(*) AS total FROM alpha_accounts WHERE siswa_id = $siswa_id");
                            $alpha_count = $alpha_check->fetch_assoc()['total'];
                            if ($alpha_count == 0) {
                                $conn->query("INSERT INTO alpha_accounts (siswa_id) VALUES ($siswa_id)");
                            }
                        }
                      ?>
                    </td>
                    
                    <!-- Aksi -->
                    <td>
                        <!-- Tombol Edit memicu modal dengan data-id -->
                        <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?= $row['id'] ?>">Edit</button>
                        <a href="?delete_siswa=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <!-- Modal Edit Siswa (akan muncul di sisi kanan) -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Siswa</h5>
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
          url: 'edit_siswa_form.php',
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
