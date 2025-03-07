<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Function to create a zip file
function createZip($files, $zipFileName) {
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE) !== TRUE) {
        return false;
    }
    foreach ($files as $file) {
        $zip->addFile($file, basename($file));
    }
    $zip->close();
    return true;
}

// Proses kembalikan tugas
if (isset($_POST['return_id']) && isset($_POST['comment'])) {
    $id = $_POST['return_id'];
    $comment = $_POST['comment'];
    $stmt = $conn->prepare("UPDATE submitted_tugas SET status = 'returned', comment = ? WHERE id = ?");
    $stmt->bind_param("si", $comment, $id);
    if ($stmt->execute()) {
        // Get the file path to return to the member
        $file_result = $conn->query("SELECT file FROM submitted_tugas WHERE id = $id");
        $file_row = $file_result->fetch_assoc();
        $file_path = $file_row['file'];
        
        // Copy the file to a return directory
        $return_dir = "uploads/returned_tugas/";
        if (!is_dir($return_dir)) {
            mkdir($return_dir, 0777, true);
        }
        $new_file_path = $return_dir . basename($file_path);
        copy($file_path, $new_file_path);

        echo "<script>alert('Tugas berhasil dikembalikan.'); window.location.href='submitted_tugas.php';</script>";
    } else {
        echo "<script>alert('Gagal mengembalikan tugas: " . $conn->error . "');</script>";
    }
}

// Handle file download
if (isset($_GET['download_id'])) {
    $id = $_GET['download_id'];
    $file_result = $conn->query("SELECT file, tugas_id, member_id FROM submitted_tugas WHERE id = $id");
    $file_row = $file_result->fetch_assoc();
    $files = json_decode($file_row['file']);
    $tugas_result = $conn->query("SELECT judul FROM tugas WHERE id = " . $file_row['tugas_id']);
    $tugas_row = $tugas_result->fetch_assoc();
    $member_result = $conn->query("SELECT username FROM members WHERE id = " . $file_row['member_id']);
    $member_row = $member_result->fetch_assoc();
    $zipFileName = $tugas_row['judul'] . ' - ' . $member_row['username'] . '.zip';

    if (count($files) > 1) {
        if (createZip($files, $zipFileName)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            readfile($zipFileName);
            unlink($zipFileName);
        } else {
            echo "<script>alert('Gagal membuat file zip.');</script>";
        }
    } else {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($files[0]) . '"');
        readfile($files[0]);
    }
}

// Update indikator for each submitted tugas
$update_indikator_query = "
    UPDATE submitted_tugas st
    JOIN tugas t ON st.tugas_id = t.id
    SET st.indikator = IF(st.tanggal <= t.deadline, 'tepat waktu', 'terlambat')
";
$conn->query($update_indikator_query);

// Ambil data tugas yang sudah dikumpulkan oleh member
$submitted_tugas_result = $conn->query("
    SELECT submitted_tugas.id, 
           submitted_tugas.tugas_id, 
           submitted_tugas.member_id, 
           submitted_tugas.file, 
           submitted_tugas.tanggal, 
           members.username AS member_name,
           tugas.judul AS tugas_judul,
           submitted_tugas.status,
           submitted_tugas.member_comment,
           submitted_tugas.indikator
    FROM submitted_tugas
    JOIN members ON submitted_tugas.member_id = members.id
    JOIN tugas ON submitted_tugas.tugas_id = tugas.id
    WHERE submitted_tugas.status != 'returned'
    ORDER BY submitted_tugas.tugas_id ASC, submitted_tugas.id ASC
");

// Ambil data tugas untuk navbar
$tugas_navbar_result = $conn->query("SELECT id, judul FROM tugas ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tugas yang Dikumpulkan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .navbar {
            margin-bottom: 20px;
        }
        .right-sidebar {
            position: fixed;
            top: 0;
            right: -25%;
            width: 25%;
            height: 100%;
            overflow-y: auto;
            background-color: #f8f9fa;
            padding: 20px;
            transition: right 0.3s;
        }
        .right-sidebar.open {
            right: 0;
        }
        .content {
            margin-right: 25%;
        }
        .toggle-btn {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            background-color: #007bff;
            color: white;
            padding: 10px;
            cursor: pointer;
            z-index: 1000;
            transition: right 0.3s;
        }
        .right-sidebar.open + .toggle-btn {
            right: 25%;
        }
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
      <?php include 'sidebar.php'; ?>

      <!-- Konten Utama -->
      <div class="col-md-9 content">
        <h2 class="text-center">Tugas yang Dikumpulkan</h2>
        <?php 
        if (isset($_GET['tugas_id'])) {
            $selected_tugas_id = $_GET['tugas_id'];
            $submitted_tugas_result = $conn->query("
                SELECT submitted_tugas.id, 
                       submitted_tugas.tugas_id, 
                       submitted_tugas.member_id, 
                       submitted_tugas.file, 
                       submitted_tugas.tanggal, 
                       members.username AS member_name,
                       tugas.judul AS tugas_judul,
                       submitted_tugas.status,
                       submitted_tugas.member_comment,
                       submitted_tugas.indikator
                FROM submitted_tugas
                JOIN members ON submitted_tugas.member_id = members.id
                JOIN tugas ON submitted_tugas.tugas_id = tugas.id
                WHERE submitted_tugas.tugas_id = $selected_tugas_id AND submitted_tugas.status != 'returned'
                ORDER BY submitted_tugas.id ASC
            ");
            
            if ($submitted_tugas_result->num_rows > 0) {
                $row = $submitted_tugas_result->fetch_assoc();
                echo '<h3>' . $row['tugas_judul'] . '</h3>';
                echo '<table class="table table-bordered">';
                echo '<thead><tr><th>No.</th><th>Nama Member</th><th>File</th><th>Tanggal</th><th>Status</th><th>Member Comment</th><th>Indikator</th><th>Aksi</th></thead><tbody>';
                $no = 1;
                do {
                    echo '<tr>';
                    echo '<td>' . $no++ . '</td>';
                    echo '<td>' . $row['member_name'] . '</td>';
                    echo '<td><a href="?download_id=' . $row['id'] . '" class="btn btn-primary btn-sm">Download</a></td>';
                    echo '<td>' . $row['tanggal'] . '</td>';
                    echo '<td>' . ($row['status'] == 'returned' ? 'Dikembalikan' : 'Diterima') . '</td>';
                    echo '<td class="comment-scroll">' . wordwrap($row['member_comment'] ?? '', 14, "<br>\n", true) . '</td>';
                    echo '<td>' . $row['indikator'] . '</td>';
                    echo '<td>
                            <button class="btn btn-warning btn-sm" onclick="showReturnModal(' . $row['id'] . ')">Kembalikan</button>
                          </td>';
                    echo '</tr>';
                } while ($row = $submitted_tugas_result->fetch_assoc());
                echo '</tbody></table>';
            } else {
                echo '<p class="text-center">Tidak ada tugas yang dikumpulkan untuk tugas ini.</p>';
            }
        } else {
            echo '<p class="text-center">Silakan pilih tugas dari sidebar di bagian kanan.</p>';
        }
        ?>
      </div>

      <!-- Right Sidebar -->
      <div class="right-sidebar" id="rightSidebar">
        <h4>Pilih Tugas</h4>
        <ul class="list-group">
          <?php while ($tugas = $tugas_navbar_result->fetch_assoc()) { ?>
            <li class="list-group-item">
              <a href="?tugas_id=<?= $tugas['id'] ?>"><?= wordwrap($tugas['judul'], 35, "<br>\n", true) ?></a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <div class="toggle-btn" id="toggleBtn">☰</div>
    </div>
  </div>
  
  <!-- Modal untuk mengembalikan tugas -->
  <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="returnModalLabel">Kembalikan Tugas</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST">
          <div class="modal-body">
            <input type="hidden" name="return_id" id="return_id">
            <div class="form-group">
              <label for="comment">Komentar:</label>
              <textarea class="form-control" id="comment" name="comment" maxlength="200" oninput="updateCharCount()"></textarea>
              <small id="charCount" class="form-text text-muted">0/200 characters</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-warning">Kembalikan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script>
    document.getElementById('toggleBtn').addEventListener('click', function() {
        var sidebar = document.getElementById('rightSidebar');
        sidebar.classList.toggle('open');
        this.classList.toggle('open');
    });

    function showReturnModal(id) {
        document.getElementById('return_id').value = id;
        $('#returnModal').modal('show');
    }

    function updateCharCount() {
        var comment = document.getElementById('comment');
        var charCount = document.getElementById('charCount');
        charCount.textContent = comment.value.length + "/200 characters";
    }
  </script>
</body>
</html>
