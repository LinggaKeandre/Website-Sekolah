<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Tambah Siswa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_siswa'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas   = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);

    $sql = "INSERT INTO siswa (nama, kelas, jurusan) VALUES ('$nama', '$kelas', '$jurusan')";
    if ($conn->query($sql) === TRUE) {
        header("Location: data_siswa.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Hapus Siswa
if (isset($_GET['delete_siswa'])) {
    $id = $_GET['delete_siswa'];
    $sql = "DELETE FROM siswa WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: data_siswa.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Mengurutkan ID dengan ASC
$result = $conn->query("SELECT * FROM siswa ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons (pastikan link ini ada sebelum sidebar ditampilkan) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <?php include 'sidebar.php'; ?>
            </div>

            <!-- Konten Utama -->
            <div class="col-md-10 p-4">
                <h2 class="mb-3">Data Siswa</h2>

                <!-- Form Tambah Siswa -->
                <div class="card p-4 mb-4">
                    <h4>Tambah Siswa</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" name="kelas" id="kelas" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" id="jurusan" class="form-control" required>
                        </div>
                        <button type="submit" name="add_siswa" class="btn btn-primary w-100">Tambah Siswa</button>
                    </form>
                </div>

                <!-- Tabel Data Siswa -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Nomor Absen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['kelas'] ?></td>
            <td><?= $row['jurusan'] ?></td>
            <td><?= $row['nomor_absen'] ?></td>
            <td>
                <a href="?edit_siswa=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="?delete_siswa=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>


            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
