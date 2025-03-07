<?php
// edit_siswa_form.php
include 'config.php';
if (!isset($_GET['id'])) {
    exit('ID tidak ditemukan.');
}
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM siswa WHERE id = $id");
$edit_row = $result->fetch_assoc();
?>

<form method="POST" action="siswa.php">
    <input type="hidden" name="id" value="<?= $edit_row['id'] ?>">
    <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= $edit_row['nama'] ?>" required>
    </div>
    <div class="form-group">
        <label>Kelas</label>    
        <select name="kelas" class="form-control" required>
            <option value="">-- Pilih Kelas --</option>
            <option value="10" <?= ($edit_row['kelas'] == '10') ? 'selected' : '' ?>>10</option>
            <option value="11" <?= ($edit_row['kelas'] == '11') ? 'selected' : '' ?>>11</option>
            <option value="12" <?= ($edit_row['kelas'] == '12') ? 'selected' : '' ?>>12</option>
        </select>
    </div>
    <div class="form-group">
        <label>Jurusan</label>
        <select name="jurusan" class="form-control" required>
            <option value="">-- Pilih Jurusan --</option>
            <option value="RPL"  <?= ($edit_row['jurusan'] == 'RPL')  ? 'selected' : '' ?>>RPL</option>
            <option value="DKV1" <?= ($edit_row['jurusan'] == 'DKV1') ? 'selected' : '' ?>>DKV1</option>
            <option value="DKV2" <?= ($edit_row['jurusan'] == 'DKV2') ? 'selected' : '' ?>>DKV2</option>
            <option value="BR"   <?= ($edit_row['jurusan'] == 'BR')   ? 'selected' : '' ?>>BR</option>
            <option value="MP"   <?= ($edit_row['jurusan'] == 'MP')   ? 'selected' : '' ?>>MP</option>
            <option value="AKL"  <?= ($edit_row['jurusan'] == 'AKL')  ? 'selected' : '' ?>>AKL</option>
        </select>
    </div>
    <div class="form-group">
        <label>Nomor Absen</label>
        <input type="number" name="nomor_absen" class="form-control" value="<?= $edit_row['nomor_absen'] ?>" min="1" max="42" required>
    </div>
    <button type="submit" name="update_siswa" class="btn btn-primary">Update Siswa</button>
    <a href="siswa.php" class="btn btn-secondary">Kembali</a>
</form>
