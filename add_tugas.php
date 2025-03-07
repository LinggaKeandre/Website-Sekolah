<?php
include 'config.php';
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$files = []; // Initialize the $files variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tugas'])) {
    $judul     = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline  = $_POST['deadline'];
    $mapel     = $_POST['mapel']; // Add mapel

    // Retrieve existing files if any
    if (isset($_POST['existing_files']) && !empty($_POST['existing_files'])) {
        $files = json_decode($_POST['existing_files'], true);
    }

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
        $sql = "INSERT INTO tugas (judul, deskripsi, deadline, mapel, files) VALUES ('$judul', '$deskripsi', '$deadline', '$mapel', '$files_json')";
        if ($conn->query($sql)) {
            header("Location: tugas.php");
            exit();
        } else {
            $error = "Gagal menambahkan tugas: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Tugas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .file-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 10px;
            display: none;
        }
        .floating-warning {
            color: red;
            position: absolute;
            background: white;
            padding: 5px;
            border: 1px solid red;
            display: none;
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
          <h2 class="text-center">Tambah Tugas</h2>
          <?php if ($error != ''): ?>
              <div class="alert alert-danger"><?= $error; ?></div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="existing_files" id="existing_files" value='<?= json_encode($files) ?>'>
              <div class="form-group">
                  <label for="judul">Judul Tugas:</label>
                  <input type="text" name="judul" id="judul" class="form-control" maxlength="50" oninput="updateCharCount('judul', 'judulCharCount', 50); validateJudul();" required>
                  <small id="judulCharCount" class="form-text text-muted">0/50 characters</small>
                  <small id="judulWarning" class="floating-warning">Judul hanya boleh mengandung huruf, spasi, dan angka.</small>
              </div>
              <div class="form-group">
                  <label for="deskripsi">Deskripsi:</label>
                  <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" maxlength="200" oninput="updateCharCount('deskripsi', 'deskripsiCharCount', 200)" required></textarea>
                  <small id="deskripsiCharCount" class="form-text text-muted">0/200 characters</small>
              </div>
              <div class="form-group">
                  <label for="mapel">Mapel:</label>
                  <select name="mapel" id="mapel" class="form-control" required>
                      <option value="Matematika">Matematika</option>
                      <option value="Bahasa Inggris">Bahasa Inggris</option>
                      <option value="Bahasa Indonesia">Bahasa Indonesia</option>
                      <option value="Ilmu Pengetahuan Alam">Ilmu Pengetahuan Alam</option>
                      <option value="Pemrograman Web">Pemrograman Web</option>
                      <option value="Penjaskes">Penjaskes</option>
                  </select>
              </div>
              <div class="form-group">
                  <label for="deadline">Deadline:</label>
                  <input type="datetime-local" name="deadline" id="deadline" class="form-control" required>
              </div>
              <div class="form-group">
                  <label for="files">Upload File (opsional):</label>
                  <div class="input-group">
                      <div class="custom-file">
                          <input type="file" name="files[]" id="files" class="custom-file-input" multiple onchange="displayFileNames()">
                          <label class="custom-file-label" for="files">Tambah File <i class="fas fa-plus"></i></label>
                      </div>
                  </div>
                  <div id="fileBox" class="file-box">
                      <ul id="fileNames">
                          <?php if (!empty($files)): ?>
                              <?php foreach ($files as $file): ?>
                                  <li><?= basename($file) ?></li>
                              <?php endforeach; ?>
                          <?php endif; ?>
                      </ul>
                  </div>
              </div>
              <button type="submit" name="add_tugas" class="btn btn-primary mt-3">Tambah Tugas</button>
              <a href="tugas.php" class="btn btn-secondary mt-3 ml-2">Kembali</a>
          </form>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script>
    function updateCharCount(inputId, countId, maxLength) {
        var input = document.getElementById(inputId);
        var charCount = document.getElementById(countId);
        charCount.textContent = input.value.length + "/" + maxLength + " characters";
    }

    function displayFileNames() {
        var fileInput = document.getElementById('files');
        var fileNames = Array.from(fileInput.files).map(file => `<li>${file.name}</li>`).join('');
        var fileBox = document.getElementById('fileBox');
        var fileNamesDisplay = document.getElementById('fileNames');
        
        fileNamesDisplay.innerHTML += fileNames;
        fileBox.style.display = 'block';
    }

    function validateJudul() {
        var judulInput = document.getElementById('judul');
        var judulWarning = document.getElementById('judulWarning');
        var valid = /^[a-zA-Z0-9 ]+$/.test(judulInput.value);
        if (!valid) {
            judulWarning.style.display = 'block';
            var rect = judulInput.getBoundingClientRect();
            judulWarning.style.top = (rect.top + window.scrollY - judulWarning.offsetHeight - 5) + 'px';
            judulWarning.style.left = (rect.left + window.scrollX) + 'px';
        } else {
            judulWarning.style.display = 'none';
        }
    }

    document.getElementById('judul').addEventListener('input', validateJudul);
  </script>
</body>
</html>
