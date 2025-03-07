<?php
include 'config.php';

if (isset($_GET['nama'])) {
    $nama = $_GET['nama'];
    $stmt = $conn->prepare("SELECT id, jurusan, kelas, nomor_absen FROM siswa WHERE nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
}
?>
