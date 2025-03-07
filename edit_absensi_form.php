<?php
include 'config.php';
if (!isset($_GET['id'])) {
    exit('ID tidak ditemukan.');
}

$id = $_GET['id'];
$edit_result = $conn->query("SELECT * FROM absensi WHERE id=$id");
if ($edit_result->num_rows == 0) {
    exit('Data absensi tidak ditemukan.');
}

$edit_row = $edit_result->fetch_assoc();

// Foto lama yang ada di database
$old_foto = $edit_row['foto']; 
?>

<form method="POST" enctype="multipart/form-data" action="absensi.php">
    <!-- Hidden input untuk menandai proses update -->
    <input type="hidden" name="update_absensi" value="1">
    <input type="hidden" name="id" value="<?= $edit_row['id'] ?>">

    <!-- Hidden input untuk menyimpan foto lama (agar tidak hilang jika tidak upload baru) -->
    <input type="hidden" id="old_foto" name="old_foto" value="<?= $old_foto ?>">

    <!-- Pilih Status -->
    <div class="form-group">
        <label>Status:</label>
        <select name="status" id="status" class="form-control" required>
            <option value="Hadir"     <?= $edit_row['status'] == 'Hadir'     ? 'selected' : '' ?>>Hadir</option>
            <option value="Sakit"     <?= $edit_row['status'] == 'Sakit'     ? 'selected' : '' ?>>Sakit</option>
            <option value="Terlambat" <?= $edit_row['status'] == 'Terlambat' ? 'selected' : '' ?>>Terlambat</option>
        </select>
    </div>
    
    <!-- Upload Foto -->
    <div class="form-group" id="uploadContainer">
        <label>Upload Foto (kosongkan jika tidak ingin mengubah):</label>
        <input type="file" name="foto" class="form-control-file" id="foto" accept="image/*">
    </div>
    
    <!-- Preview Foto -->
    <div class="form-group" id="previewContainer">
        <label>Preview:</label><br>
        <img 
          id="previewImage" 
          src="<?= !empty($old_foto) ? $old_foto : '#' ?>" 
          alt="Preview" 
          class="img-thumbnail" 
          style="max-width:200px; <?= empty($old_foto) ? 'display:none;' : '' ?>"
        >
    </div>

    <!-- Tambahkan elemen untuk pesan peringatan -->
    <div id="alertMessage" class="alert alert-danger" style="display:none;">
        Foto wajib diunggah jika status Hadir atau Terlambat.
    </div>
    
    <button type="submit" class="btn btn-primary" id="updateButton">Update Absensi</button>
</form>

<script>
// Bisa berisi foto lama (old_foto) atau file baru dari local
var currentPreviewURL = "";
var oldFoto = document.getElementById("old_foto").value;

// Jika memang ada old_foto, jadikan default currentPreviewURL
if (oldFoto) {
  currentPreviewURL = oldFoto;
}

// Fungsi menampilkan/menyembunyikan field upload & preview berdasarkan status
function toggleUploadFields() {
  var statusValue      = document.getElementById("status").value;
  var uploadContainer  = document.getElementById("uploadContainer");
  var previewContainer = document.getElementById("previewContainer");
  var previewImage     = document.getElementById("previewImage");
  var updateButton     = document.getElementById("updateButton");

  if (statusValue === "Sakit") {
    // Sembunyikan field upload dan preview
    uploadContainer.style.display  = "none";
    previewContainer.style.display = "none";
    // Kosongkan input file dan preview image
    document.getElementById("foto").value = "";
    previewImage.src = "#";
    previewImage.style.display = "none";
    // Aktifkan tombol update
    updateButton.disabled = false;
    updateButton.classList.remove('btn-secondary');
    updateButton.classList.add('btn-primary');
  } else {
    // Tampilkan field upload
    uploadContainer.style.display = "block";
    
    // Jika currentPreviewURL ada isinya (entah foto lama atau foto baru),
    // maka tampilkan preview
    if (currentPreviewURL !== "") {
      previewContainer.style.display = "block";
      previewImage.src = currentPreviewURL;
      previewImage.style.display = "block";
      // Aktifkan tombol update
      updateButton.disabled = false;
      updateButton.classList.remove('btn-secondary');
      updateButton.classList.add('btn-primary');
    } else {
      // Kalau memang belum ada foto sama sekali, sembunyikan preview
      previewContainer.style.display = "none";
      // Nonaktifkan tombol update
      updateButton.disabled = true;
      updateButton.classList.remove('btn-primary');
      updateButton.classList.add('btn-secondary');
    }
  }
}

// Panggil toggleUploadFields() saat pertama kali dimuat
toggleUploadFields();

// Jika user mengubah status, panggil lagi
document.getElementById("status").addEventListener("change", toggleUploadFields);

// Jika user memilih file baru, perbarui currentPreviewURL dengan file local
document.getElementById("foto").onchange = function(event) {
  var previewImage     = document.getElementById("previewImage");
  var previewContainer = document.getElementById("previewContainer");
  var updateButton     = document.getElementById("updateButton");

  if (event.target.files && event.target.files[0]) {
    currentPreviewURL         = URL.createObjectURL(event.target.files[0]);
    previewImage.src          = currentPreviewURL;
    previewImage.style.display = "block";
    previewContainer.style.display = "block";
    // Aktifkan tombol update
    updateButton.disabled = false;
    updateButton.classList.remove('btn-secondary');
    updateButton.classList.add('btn-primary');
  } else {
    // Jika user menghapus file yang dipilih
    currentPreviewURL = oldFoto; // Kembalikan ke foto lama (jika ada)
    if (currentPreviewURL) {
      previewImage.src = currentPreviewURL;
      previewImage.style.display = "block";
      previewContainer.style.display = "block";
      // Aktifkan tombol update
      updateButton.disabled = false;
      updateButton.classList.remove('btn-secondary');
      updateButton.classList.add('btn-primary');
    } else {
      previewImage.style.display = "none";
      previewContainer.style.display = "none";
      // Nonaktifkan tombol update
      updateButton.disabled = true;
      updateButton.classList.remove('btn-primary');
      updateButton.classList.add('btn-secondary');
    }
  }
};

// Validasi form sebelum submit
document.querySelector('form').onsubmit = function() {
  var statusValue = document.getElementById("status").value;
  var fotoInput = document.getElementById("foto");
  if ((statusValue === "Hadir" || statusValue === "Terlambat") && fotoInput.files.length === 0 && !oldFoto) {
    document.getElementById("alertMessage").style.display = "block";
    return false; // Batalkan submit
  }
  return true; // Lanjutkan submit
};
</script>
