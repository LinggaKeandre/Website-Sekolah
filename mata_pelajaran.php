<?php
include 'config.php';
date_default_timezone_set('Asia/Jakarta'); // Set timezone ke Jakarta

if (!isset($_SESSION['admin']) && !isset($_SESSION['member'])) {
    header("Location: login.php");
    exit();
}

$jadwal = [
    'Senin' => [
        ['Matematika', '07:00-08:30'],
        ['Bahasa Indonesia', '08:30-10:00'],
        ['IPA', '10:15-11:45'],
        ['IPS', '12:30-14:00']
    ],
    'Selasa' => [
        ['Bahasa Inggris', '07:00-08:30'],
        ['Matematika', '08:30-10:00'],
        ['Seni Budaya', '10:15-11:45'],
        ['Penjaskes', '12:30-14:00']
    ],
    'Rabu' => [
        ['Fisika', '07:00-08:30'],
        ['Kimia', '08:30-10:00'],
        ['Biologi', '10:15-11:45'],
        ['Sejarah', '12:30-14:00']
    ],
    'Kamis' => [
        ['Geografi', '07:00-08:30'],
        ['Ekonomi', '08:30-10:00'],
        ['Sosiologi', '10:15-11:45'],
        ['PKN', '12:30-14:00']
    ],
    'Jumat' => [
        ['Agama', '07:00-08:30'],
        ['Bahasa Inggris', '08:30-10:00'],
        ['Matematika', '10:15-11:45'],
        ['Kesenian', '12:30-14:00']
    ]
];

function isCurrentSubject($timeRange) {
    $now = new DateTime();
    list($start, $end) = explode('-', $timeRange);
    $todayDate = $now->format('Y-m-d');
    // Buat objek DateTime dengan tanggal hari ini dan waktu yang ditentukan
    $startTime = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $start);
    $endTime   = DateTime::createFromFormat('Y-m-d H:i', $todayDate . ' ' . $end);
    return $now >= $startTime && $now <= $endTime;
}

// Dapatkan hari dalam Bahasa Inggris dan mapping ke Bahasa Indonesia
$todayEnglish = date('l');
$hariMapping = [
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat'
];
$todayIndo = $hariMapping[$todayEnglish] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Mata Pelajaran</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .current-subject {
            background-color: #d4edda;
        }
    </style>
</head>
<body>
  <?php include 'includes/clock.php'; ?>
  <div class="container-fluid mt-3">
    <div class="row">
      <!-- Sidebar -->
      <?php
      if (isset($_SESSION['admin'])) {
          include 'sidebar.php';
      } else {
          include 'member_sidebar.php';
      }
      ?>

      <!-- Konten Utama -->
      <div class="col-md-9">
        <h2 class="text-center">Jadwal Mata Pelajaran</h2>
        <?php if ($todayIndo): ?>
          <?php foreach ($jadwal as $hariName => $mapel): ?>
            <h3><?= $hariName ?></h3>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Mata Pelajaran</th>
                  <th>Jam</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($mapel as $item): ?>
                  <tr class="<?= ($todayIndo === $hariName && isCurrentSubject($item[1])) ? 'current-subject' : '' ?>">
                    <td><?= $item[0] ?></td>
                    <td><?= $item[1] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert alert-info text-center">Tidak ada kelas pada hari ini.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS dan dependensinya -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
