<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Sekolah</title>
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="brand">SMKN 40 Jakarta</div>
            <div class="nav-links">
                <a href="login_member.php" class="login-btn">Login</a>
            </div>
        </div>
    </nav>

    <!-- Teks/Gambar Berjalan (Marquee) tepat di bawah Navbar -->
    <section class="marquee-container">
  <!-- Kotak hijau FLASH STORY -->
  <div class="flash-story">
    <span>FLASH STORY</span>
  </div>

  <!-- Pembungkus marquee -->
  <div class="marquee-wrapper">
    <div class="marquee">
      <!-- Blok 1 -->
      <div class="marquee-content">
        <span>PPDB - PENDAFTARAN & PEMILIHAN SEKOLAH 10 JUNI 2024</span>
        <span>Juara 2 Lomba Web Development (AWS 2023)</span>
        <span>Juara 2 Lomba Business Plan Digital (Baznaz 2023)</span>
        <span>Medali Perak (Olimpiade Sains dan Statistika Nasional 2023)</span>
        <span>Juara 1 dan Juara Presentasi Terbaik Web Development (AWS 2023)</span>
      </div>
      <!-- Blok 2 (duplikasi Blok 1) -->
      <div class="marquee-content">
        <span>PPDB - PENDAFTARAN & PEMILIHAN SEKOLAH 10 JUNI 2024</span>
        <span>Juara 2 Lomba Web Development (AWS 2023)</span>
        <span>Juara 2 Lomba Business Plan Digital (Baznaz 2023)</span>
        <span>Medali Perak (Olimpiade Sains dan Statistika Nasional 2023)</span>
        <span>Juara 1 dan Juara Presentasi Terbaik Web Development (AWS 2023)</span>
      </div>
    </div>
  </div>
</section>
    <!-- Main Content -->
    <main class="container">
        <!-- Sejarah Sekolah -->
        <section class="section">
            <h2>Sambutan Kepala Sekolah</h2>
            <p>Assalamualaikum warahmatullahi wabarakatuh

salam sejahtera untuk kita semua SMK Negeri 40 Jakarta berada diwilayah strategis Jakarta Timur dengan luas 2060 M2 memiliki empat lantai, jumlah peserta didik 638 orang dengan 18 rombongan belajar 5 program keahlian ada di SMK Negeri 40 Jakarta yakni

SMK Negeri 40 Jakarta juga 5 program konsentrasi keahlian yakni :

Konsentrasi Keahlian Akuntansi Keuangan
Konsentrasi Keahlian Manajemen Pemasaran
Konsentrasi Keahlian Manajemen Perkantoran,
Konsentrasi Keahlian Desain Komunikasi Visual
Konsentrasi Keahlian Rekayasa Perangkat Lunak
SMK Negeri 40 Jakarta juga memiliki 37 orang pendidik dan 12 orang tenaga kependidikan yang semuanya berkompeten di bidangnya terutama sebagai tenaga pendidik produktif yang mendukung kompetensi lulusan dan telah memiliki sertifikat asesor yang relevan sebanyak 11 orang yang terdiri dari asesor kompetensi dan asesor metodologi.

SMK Negeri 40 Jakarta menerapkan pembelajaran model teaching factory bekerja sama dengan dunia usaha dan dunia industri dalam praktik kerja lapangan SMK Negeri 40 Jakarta memiliki visi terwujudnya lulusan yang bertakwa berkualitas unggul dan memiliki kompetensi sesuai Standar Industri dalam revolusi industri 4.0.

Salah satu misi untuk mewujudkan visi itu adalah meningkatkan kompetensi peserta didik sesuai Standar Industri dan memiliki jiwa wirausaha.

Wassalamualaikum warahmatullahi wabarakatuh</p>
        </section>

        <!-- Kompetensi Keahlian -->
        <section class="section">
            <h2>Kompetensi Keahlian</h2>
            <ul>
                <li>Rekayasa Perangkat Lunak</li>
                <li>Desain Komunikasi Visual</li>
                <li>Manajemen Perkantoran</li>
                <li>Bisnis Ritel</li>
                <li>Akuntansi</li>
            </ul>
        </section>

        <!-- Ekstrakurikuler -->
        <section class="section">
            <h2>Ekstrakurikuler</h2>
            <div class="grid">
                <div class="card">
                    PASPRAPU (Pasukan Pramuka Empat Puluh)
                    <img src="ekskul/pasprapu.jpg" alt="PASPRAPU" class="tooltip-image">
                </div>
                <div class="card">
                    PASKIBRA (GARATIKA)
                    <img src="ekskul/paskibra.jpeg" alt="PASKIBRA" class="tooltip-image">
                </div>
                <div class="card">
                    PMR (Palang Merah Remaja)
                    <img src="ekskul/pmr.jpg" alt="PMR" class="tooltip-image">
                </div>
                <div class="card">
                    Basket
                    <img src="ekskul/basket.jpg" alt="Basket" class="tooltip-image">
                </div>
                <div class="card">
                    Voli
                    <img src="ekskul/voli.jpg" alt="Voli" class="tooltip-image">
                </div>
                <div class="card">
                    Futsal
                    <img src="ekskul/futsal.jpg" alt="Futsal" class="tooltip-image">
                </div>
                <div class="card">
                    Drumband
                    <img src="ekskul/drumband.jpg" alt="Drumband" class="tooltip-image">
                </div>
                <div class="card">
                    Tari
                    <img src="ekskul/tari.jpg" alt="Tari" class="tooltip-image">
                </div>
                <div class="card">
                    Silat
                    <img src="ekskul/silat.jpeg" alt="Silat" class="tooltip-image">
                </div>
                <div class="card">
                    Marawis
                    <img src="ekskul/marawis.jpeg" alt="Marawis" class="tooltip-image">
                </div>
                <div class="card">
                    ROHKRIS (Rohani Kristen)
                    <img src="ekskul/rohkris.jpg" alt="ROHKRIS" class="tooltip-image">
                </div>
                <div class="card">
                    ROHIS (Rohani Islam)
                    <img src="ekskul/rohis.jpeg" alt="ROHIS" class="tooltip-image">
                </div>
                <div class="card">
                    Teater
                    <img src="ekskul/teater.jpg" alt="Teater" class="tooltip-image">
                </div>
                <div class="card">
                    PIK
                    <img src="ekskul/pik.jpg" alt="PIK" class="tooltip-image">
                </div>
                <div class="card">
                    Sinematografi
                    <img src="ekskul/sinematografi.jpg" alt="Sinematografi" class="tooltip-image">
                </div>
                <div class="card">
                    Desain web
                    <img src="ekskul/desainweb.jpg" alt="Desain Web" class="tooltip-image">
                </div>
                <div class="card">
                    Desain grafis
                    <img src="ekskul/desaingrafis.jpeg" alt="Desain Grafis" class="tooltip-image">
                </div>
            </div>
        </section>

        <!-- Fasilitas Sekolah -->
        <section class="section">
            <h2>Fasilitas Sekolah</h2>
            <div class="grid">
                <div class="card">
                    Perpustakaan
                    <img src="fasilitas/perpustakaan.jpg" alt="Perpustakaan" class="tooltip-image">
                </div>
                <div class="card">
                    Lab Komputer
                    <img src="fasilitas/labkomputer.jpg" alt="Lab Komputer" class="tooltip-image">
                </div>
                <div class="card">
                    Bank Mini
                    <img src="fasilitas/bankmini.jpeg" alt="Bank Mini" class="tooltip-image">
                </div>
                <div class="card">
                    Kantin
                    <img src="fasilitas/kantin.jpg" alt="Kantin" class="tooltip-image">
                </div>
                <div class="card">
                    UKS (Usaha Kesehatan Sekolah)
                    <img src="fasilitas/UsahaKesehatanSekolah.jpg" alt="UKS" class="tooltip-image">
                </div>
                <div class="card">
                    Masjid
                    <img src="fasilitas/masjid.jpeg" alt="Masjid" class="tooltip-image">
                </div>
                <div class="card">
                    Aula Sekolah
                    <img src="fasilitas/aula.png" alt="Aula Sekolah" class="tooltip-image">
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>SMKN 40 Jakarta</p>
    </footer>

    <!-- Social Media Icons -->
    <div class="social-icons">
        <a href="https://facebook.com" target="_blank">
            <img src="assets/facebook.png" alt="Facebook">
        </a>
        <a href="https://twitter.com" target="_blank">
            <img src="assets/twitter.png" alt="Twitter">
        </a>
        <a href="https://instagram.com" target="_blank">
            <img src="assets/instagram.png" alt="Instagram">
        </a>
        <a href="https://youtube.com" target="_blank">
            <img src="assets/youtube.png" alt="YouTube">
        </a>
    </div>
</body>
</html>
