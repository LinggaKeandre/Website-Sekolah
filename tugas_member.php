<?php
include 'config.php';
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit();
}

// Define the subjects
$subjects = [
    'Matematika' => 'tugas_matematika.php',
    'Bahasa Inggris' => 'tugas_bahasa_inggris.php',
    'Bahasa Indonesia' => 'tugas_bahasa_indonesia.php',
    'Ilmu Pengetahuan Alam' => 'tugas_ipa.php',
    'Pemrograman Web' => 'tugas_pemrograman_web.php',
    'Penjaskes' => 'tugas_penjaskes.php'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Tugas Member</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .subject-box {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            margin: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .subject-box:hover {
            background-color: #f0f0f0;
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
        <h2 class="text-center">Pilih Mapel</h2>
        <div class="row">
            <?php foreach ($subjects as $subject => $link): ?>
                <div class="col-md-4">
                    <div class="subject-box" onclick="window.location.href='<?= $link ?>'">
                        <?= $subject ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
