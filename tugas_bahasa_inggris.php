<?php
include 'config.php';
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit();
}

// Ambil data tugas untuk Bahasa Inggris
$tugas_result = $conn->query("SELECT * FROM tugas WHERE mapel = 'Bahasa Inggris' ORDER BY id ASC");

// Ambil data tugas yang sudah dikumpulkan oleh member
$submitted_tugas_result = $conn->query("
    SELECT submitted_tugas.id, 
           submitted_tugas.tugas_id, 
           submitted_tugas.member_id, 
           submitted_tugas.file, 
           submitted_tugas.tanggal, 
           submitted_tugas.status,
           submitted_tugas.comment AS admin_comment,
           submitted_tugas.member_comment,
           members.username AS member_name,
           tugas.judul AS tugas_judul
    FROM submitted_tugas
    JOIN members ON submitted_tugas.member_id = members.id
    JOIN tugas ON submitted_tugas.tugas_id = tugas.id
    WHERE submitted_tugas.member_id = {$_SESSION['member_id']}
    AND tugas.mapel = 'Bahasa Inggris'
    ORDER BY submitted_tugas.tugas_id ASC, submitted_tugas.id ASC
");

// Buat array untuk menyimpan tugas yang sudah dikumpulkan oleh member
$submitted_tugas = [];
while ($submitted_row = $submitted_tugas_result->fetch_assoc()) {
    $submitted_tugas[$submitted_row['tugas_id']] = $submitted_row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tugas Bahasa Inggris</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .comment-scroll {
            max-height: 100px;
            overflow-y: auto;
            word-wrap: break-word;
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
        <h2 class="text-center">Tugas Bahasa Inggris</h2>

        <!-- Tabel Data Tugas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul Tugas</th>
                    <th>Deskripsi</th>
                    <th>Deadline</th>
                    <th>File</th>
                    <th>Admin Comment</th>
                    <th>Member Comment</th>
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
                    <td class="comment-scroll">
                        <?= isset($submitted_tugas[$row['id']]) ? wordwrap($submitted_tugas[$row['id']]['admin_comment'] ?? '', 14, "<br>\n", true) : '' ?>
                    </td>
                    <td class="comment-scroll">
                        <?= isset($submitted_tugas[$row['id']]) ? wordwrap($submitted_tugas[$row['id']]['member_comment'] ?? '', 14, "<br>\n", true) : '' ?>
                    </td>
                    <td>
                        <?php if (isset($submitted_tugas[$row['id']])): ?>
                            <?php if ($submitted_tugas[$row['id']]['status'] == 'returned'): ?>
                                <a href="submit_tugas.php?tugas_id=<?= $row['id'] ?>" class="btn btn-success btn-sm mt-2">Kirim Tugas</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="submit_tugas.php?tugas_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Kirim Tugas</a>
                        <?php endif; ?>
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
