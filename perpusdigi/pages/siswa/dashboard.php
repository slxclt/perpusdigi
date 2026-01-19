<?php
require_once __DIR__ . "/../../config/config.php";
session_start();

// Pastikan siswa sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['nama'];

// --- Ambil data dari database ---
$count_books = $conn->query("SELECT COUNT(*) AS c FROM books")->fetch_assoc()['c'];

// Hitung jumlah buku yang dipinjam oleh siswa ini
$count_borrowed = $conn->query("SELECT COUNT(*) AS c FROM borrow_records WHERE user_id = '$user_id'")->fetch_assoc()['c'];

// Hitung hari aktif berdasarkan catatan login (buat tabel jika belum ada)
$conn->query("CREATE TABLE IF NOT EXISTS user_logins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    login_date DATE NOT NULL
)");

$today = date('Y-m-d');
$check_today = $conn->query("SELECT * FROM user_logins WHERE user_id='$user_id' AND login_date='$today'");
if ($check_today->num_rows === 0) {
    $conn->query("INSERT INTO user_logins (user_id, login_date) VALUES ('$user_id', '$today')");
}

// Hitung berapa hari aktif siswa ini
$count_active_days = $conn->query("SELECT COUNT(DISTINCT login_date) AS c FROM user_logins WHERE user_id='$user_id'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa | PerpusDigi</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link href="http://localhost/perpusdigi/assets/css/siswa.css" rel="stylesheet">
  <link rel="icon" href="http://localhost/perpusdigi/assets/img/book.png" type="image/x-icon">

</head>
<body>

<!-- 🌙 Navbar -->
<nav class="new-navbar">
  <div class="nav-left">
    <span class="nav-logo">📚 PerpusDigi</span>
  </div>
  <div class="nav-right">
    <button id="themeToggle" class="theme-toggle" title="Ganti Mode">🌞</button>
    <a href="../../auth/logout.php" class="logout-btn">Logout</a>
  </div>
</nav>

<!-- 🏠 Konten -->
<div class="dashboard-container fade-in">
  <div class="welcome-card">
      <h2>Halo, <?php echo htmlspecialchars($name); ?> 👋</h2>
      <p>Selamat datang di <span class="brand">PerpusDigi</span>.<br>
      Temukan buku menarik dan lihat riwayat peminjamanmu di bawah ini!</p>
  </div>

  <!-- 📊 Statistik -->
  <div class="stats-row">
      <div class="stat-card">
          <img src="http://localhost/perpusdigi/assets/img/loading.gif" alt="Buku">
          <h4>Jumlah Buku</h4>
          <p><?php echo $count_books; ?></p>
      </div>
      <div class="stat-card">
          <img src="http://localhost/perpusdigi/assets/img/borrow.png" alt="Riwayat">
          <h4>Buku Dipinjam</h4>
          <p><?php echo $count_borrowed; ?></p>
      </div>
      <div class="stat-card">
          <img src="http://localhost/perpusdigi/assets/img/online.gif" alt="Anggota">
          <h4>Hari Aktif</h4>
          <p><?php echo $count_active_days; ?></p>
      </div>
  </div>

  <!-- 📚 Aksi -->
  <div class="action-row">
      <div class="card-action">
          <img src="http://localhost/perpusdigi/assets/img/books.gif" alt="Buku" class="card-img">
          <h3>Lihat Koleksi Buku</h3>
          <p>Jelajahi berbagai buku digital favoritmu!</p>
          <a href="books.php" class="btn-action">Lihat Buku</a>
      </div>
      <div class="card-action">
          <img src="http://localhost/perpusdigi/assets/img/history.gif" alt="History" class="card-img">
          <h3>Riwayat Peminjaman</h3>
          <p>Lihat catatan peminjaman dan pengembalianmu.</p>
          <a href="history.php" class="btn-action">Riwayat Saya</a>
      </div>
  </div>

  <div class="quote">
      <p>📖 “Membaca adalah jendela dunia — buka satu halaman, temukan seribu kehidupan.”</p>
      <p>#sadidganteng</p>
  </div>
</div>

<script>
const toggle = document.getElementById('themeToggle');
const body = document.body;

if (localStorage.getItem('dark-mode') === 'true') {
  body.classList.add('dark-mode');
  toggle.textContent = '🌙';
}

toggle.addEventListener('click', () => {
  body.classList.toggle('dark-mode');
  const isDark = body.classList.contains('dark-mode');
  toggle.textContent = isDark ? '🌙' : '🌞';
  localStorage.setItem('dark-mode', isDark);
});
</script>

</body>
</html>
