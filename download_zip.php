<?php
include 'config.php';

if (!isset($_SESSION['admin']) && !isset($_SESSION['member'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['tugas_id']) && isset($_GET['judul'])) {
    $tugas_id = $_GET['tugas_id'];
    $judul = urldecode($_GET['judul']);
    $file_result = $conn->query("SELECT files FROM tugas WHERE id = $tugas_id");
    $file_row = $file_result->fetch_assoc();
    $files = json_decode($file_row['files']);
    $zipFileName = $judul . '.zip';

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

    if (createZip($files, $zipFileName)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        readfile($zipFileName);
        unlink($zipFileName);
    } else {
        echo "<script>alert('Gagal membuat file zip.');</script>";
    }
}
?>
