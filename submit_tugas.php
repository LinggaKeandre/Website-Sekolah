<?php
include 'config.php';
if (!isset($_SESSION['member'])) {
    header("Location: login_member.php");
    exit();
}

if (!isset($_SESSION['member_id'])) {
    die("Error: member_id is not set in the session.");
}

$tugas_id = $_GET['tugas_id'] ?? '';
if ($tugas_id == '') {
    header("Location: tugas_member.php");
    exit();
}

$error = '';
$comment = $_POST['comment'] ?? '';
$files = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_tugas'])) {
    $member_id = $_SESSION['member_id'];
    $comment = $_POST['comment'] ?? '';

    if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {
        $target_dir = "uploads/tugas/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        foreach ($_FILES['files']['name'] as $key => $filename) {
            if ($_FILES['files']['error'][$key] == 0) {
                $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
                $new_filename = $target_dir . uniqid() . "." . $file_extension;
                if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $new_filename)) {
                    $files[] = $new_filename;
                } else {
                    $error = "Gagal mengupload file.";
                    break;
                }
            }
        }
    }

    if ($error == '') {
        $files_json = json_encode($files);
        $sql = "INSERT INTO submitted_tugas (tugas_id, member_id, file, tanggal, member_comment) VALUES ('$tugas_id', '$member_id', '$files_json', NOW(), '$comment')";
        if ($conn->query($sql)) {
            header("Location: tugas_member.php");
            exit();
        } else {
            $error = "Gagal mengirim tugas: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kirim Tugas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .file-box {
            border: 1px solid #ccc;
            padding: 10px;
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
          <h2 class="text-center">Kirim Tugas</h2>
          <?php if ($error != ''): ?>
              <div class="alert alert-danger"><?= $error; ?></div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">
              <div class="form-group">
                  <label for="files">Upload File:</label>
                  <div class="input-group">
                      <div class="custom-file">
                          <input type="file" name="files[]" id="files" class="custom-file-input" multiple required onchange="displayFileNames()">
                          <label class="custom-file-label" for="files">Tambah File <i class="fas fa-plus"></i></label>
                      </div>
                  </div>
                  <div id="fileBox" class="file-box">
                      <ul id="fileNames"></ul>
                  </div>
              </div>
              <div class="form-group">
                  <label for="comment">Komentar (Opsional):</label>
                  <textarea class="form-control" id="comment" name="comment" maxlength="200" oninput="updateCharCount()"><?= htmlspecialchars($comment) ?></textarea>
                  <small id="charCount" class="form-text text-muted"><?= strlen($comment) ?>/200 characters</small>
              </div>
              <button type="submit" name="submit_tugas" class="btn btn-primary mt-3">Kirim Tugas</button>
              <a href="tugas_member.php" class="btn btn-secondary mt-3 ml-2">Kembali</a>
          </form>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script>
    function updateCharCount() {
        var comment = document.getElementById('comment');
        var charCount = document.getElementById('charCount');
        charCount.textContent = comment.value.length + "/200 characters";
    }

    function displayFileNames() {
        var fileInput = document.getElementById('files');
        var fileNames = Array.from(fileInput.files).map(file => `<li>${file.name}</li>`).join('');
        var fileBox = document.getElementById('fileBox');
        var fileNamesDisplay = document.getElementById('fileNames');
        
        fileNamesDisplay.innerHTML = fileNames;
        fileBox.style.display = 'block';
    }
  </script>
</body>
</html>
