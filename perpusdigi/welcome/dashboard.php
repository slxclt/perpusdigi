<?php
session_start();
require_once __DIR__ . "/../config/config.php";

// Cek login dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin | PerpusDigi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="http://localhost/perpusdigi/assets/css/welcome.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <a href="#" class="brand">📚 PerpusDigi</a>
    <button class="toggle-dark" id="toggleDark" title="Ganti Mode">🌙</button>
  </nav>

  <!-- Dashboard Box -->
  <div class="dashboard-box">
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?> 👋</h2>
    <p>Anda sedang berada di <strong>Dashboard Admin</strong>.</p>
    <a href="../pages/admin/dashboard.php" class="btn btn-login mt-3">Lanjut ke Halaman Admin</a>
  </div>
  <!-- Dark Mode Script -->
  <script>
    const toggleBtn = document.getElementById('toggleDark');
    const body = document.body;
    const dark = localStorage.getItem('darkMode') === 'true';
    if (dark) body.classList.add('dark');
    toggleBtn.textContent = dark ? '☀️' : '🌙';

    toggleBtn.addEventListener('click', () => {
      body.classList.toggle('dark');
      const active = body.classList.contains('dark');
      localStorage.setItem('darkMode', active);
      toggleBtn.textContent = active ? '☀️' : '🌙';
    });
  </script>
</body>
</html>
